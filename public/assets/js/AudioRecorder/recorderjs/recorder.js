/*License (MIT)

Copyright В© 2013 Matt Diamond

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated 
documentation files (the "Software"), to deal in the Software without restriction, including without limitation 
the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and 
to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of 
the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO 
THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE 
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF 
CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER 
DEALINGS IN THE SOFTWARE.
*/

(function(window){

  var WORKER_PATH = '/assets/js/AudioRecorder/recorderjs/recorderWorker.js';

  var Recorder = function(source, cfg){
    var config = cfg || {};
    var bufferLen = config.bufferLen || 4096;
    this.context = source.context;
    if(!this.context.createScriptProcessor){
       this.node = this.context.createJavaScriptNode(bufferLen, 2, 2);
    } else {
       this.node = this.context.createScriptProcessor(bufferLen, 2, 2);
    }
   
    var worker = new Worker(config.workerPath || WORKER_PATH);
    worker.postMessage({
      command: 'init',
      config: {
        sampleRate: this.context.sampleRate
      }
    });
    var recording = false,
      currCallback;

    this.node.onaudioprocess = function(e){
      if (!recording) return;
      worker.postMessage({
        command: 'record',
        buffer: [
          e.inputBuffer.getChannelData(0),
          e.inputBuffer.getChannelData(1)
        ]
      });
    }

    this.configure = function(cfg){
      for (var prop in cfg){
        if (cfg.hasOwnProperty(prop)){
          config[prop] = cfg[prop];
        }
      }
    }

    this.record = function(){
      recording = true;
    }

    this.stop = function(){
      recording = false;
    }

    this.clear = function(){
      worker.postMessage({ command: 'clear' });
    }

    this.getBuffers = function(cb) {
      currCallback = cb || config.callback;
      worker.postMessage({ command: 'getBuffers' })
    }

    this.exportWAV = function(cb, type){
      currCallback = cb || config.callback;
      type = type || config.type || 'audio/wav';
      if (!currCallback) throw new Error('Callback not set');
      worker.postMessage({
        command: 'exportWAV',
        type: type
      });
    }

    this.exportMonoWAV = function(cb, type){
      currCallback = cb || config.callback;
      type = type || config.type || 'audio/wav';
      if (!currCallback) throw new Error('Callback not set');
      worker.postMessage({
        command: 'exportMonoWAV',
        type: type
      });
    }

    worker.onmessage = function(e){
      var blob = e.data;
      currCallback(blob);
    }

    source.connect(this.node);
    this.node.connect(this.context.destination);   // if the script node is not connected to an output the "onaudioprocess" event is not triggered in chrome.
  };

/*
* Blob uploader:
*/
  window.BlobBuilder = window.MozBlobBuilder || window.WebKitBlobBuilder ||
      window.BlobBuilder;

  /**
   * @param blobOrFile chunk
   * @param filename name of stored file
   * @param current current chunk number
   * @param total total chunk count
   * */
  function upload(blobOrFile, filename, current, total) {
    var xhr = new XMLHttpRequest();
    var fd = new FormData();
    fd.append("chunk", blobOrFile);
    fd.append("name", filename);
    fd.append("video_id", document.getElementById('video_id').getAttribute('data-video-id'));
    fd.append("total", total);
    fd.append("current", current);
    fd.append("_token", $('meta[name="csrf-token"]').attr('content'));
    xhr.open('POST', '/review/store-audio', false); // synchronous request
    xhr.onload = function(e) {
      //console.info(e);
      $('#loader').show();
    };
    xhr.send(fd);
    if (xhr.status === 200) {
      var result = JSON.parse(xhr.responseText);
      if(result.name) {
        sendStoreRequest(result.name);
      }
    } else {
      $.notify("Saving audio review error!");
    }
    // uncomment nether event for async:
    /*xhr.onreadystatechange = function() {
      if (xhr.readyState == 4 && xhr.status == 200) {
        var result = JSON.parse(xhr.response);
        if(result.name) {
          sendStoreRequest(result.name);
        }
      } else if(xhr.status != 200) {
        $.notify("Saving audio review error!");
      }
    };*/
  }

  function sendStoreRequest(filename) {
    var form = new FormData(),
        request = new XMLHttpRequest();
    form.append("filename", filename);
    form.append("video_id", document.getElementById('video_id').getAttribute('data-video-id'));
    form.append("owner_id", document.getElementById('user_id').getAttribute('data-user-id'));
    form.append("play_time", JSON.stringify(window.play_time));
    request.open(
        "POST",
        "/review",
        true
    );
    request.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
    request.onreadystatechange = function() {
      if (request.readyState == 4 && request.status == 200) {
        var response = JSON.parse(request.response),
            status_block = document.getElementById("status_block");
        if(response.review_id) {
          $("#review_id").val(response.review_id);
          $("#control_wrapper").hide();
          $('#loader').hide();
          $("#save_review_rating_btn").removeAttr("disabled");
        }else{
          status_block.classList.remove("alert-success", "alert-danger");
          status_block.classList.add("alert-" + response.status);
          document.getElementById("status").innerHTML = response.message;
          status_block.removeAttribute("hidden");
        }
      }
    };
    request.send(form);
  }


  Recorder.setupDownload = function(blob, filename) {
    const BYTES_PER_CHUNK = 1024 * 1000; //chunk size ~2MB
    const SIZE = blob.size;
    var start = 0,
        chunk_count = Math.ceil(blob.size/BYTES_PER_CHUNK), //total chunk count
        end = BYTES_PER_CHUNK,
        current = 0; // current chunk
    filename = Math.round((Math.pow(36, 7) - Math.random() * Math.pow(36, 6))).toString(36).slice(1) + filename; //random filename prefix
    while(start < SIZE) {
      var chunk = blob.slice(start, end);
      upload(chunk, filename, ++current, chunk_count);
      start = end;
      end = start + BYTES_PER_CHUNK;
    }

    //var url = (window.URL || window.webkitURL).createObjectURL(blob);

    //    var link = document.getElementById("save");
    //    link.href = url;
    //    link.download = filename || 'output.wav';
  };

  window.Recorder = Recorder;

})(window);