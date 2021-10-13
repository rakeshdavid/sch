
var audio_context;
var recorder;

function startUserMedia(stream) {
    var input = audio_context.createMediaStreamSource(stream);
    console.log('audio processor: media stream created.');
    recorder = new Recorder(input, {numChannels:1});
    console.log('audio processor: recorder initialised.');
}

function toggleRecording( e ) {
    if (e.classList.contains("recording")) {
        recorder.stop();
        e.classList.remove("recording");
        recorder.exportWAV( doneEncoding );
    } else {
        // start recording
        if (!recorder) {
            $("#control_wrapper").hide();
            $('#loader').hide();
            $("#video_blk").notify("Audio recording device error! Please, check your system  settings.", {
                autoHideDelay: 25000,
                elementPosition: "top center"
            });
            sendVideoStatusFallback();
            return;
        }

        e.classList.add("recording");
        recorder.clear();
        recorder.record();
    }
}

function doneEncoding( blob ) {
    var button = $("#record_btn"),
        button_text = button.html(),
        button_i = '<i class="fa fa-spinner fa-pulse fa-fw"></i>';
    button.html(button_i + "Saving...").attr({disabled: "disabled"});
    Recorder.setupDownload( blob, "_reviewAudio" + ".wav" );
}
Recorder.setupDownload = function(blob, filename) {
    const BYTES_PER_CHUNK = 2048 * 1000; //chunk size ~2MB
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
};

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
        "/save-audition-review",
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

function sendVideoStatusFallback(video_id) {
    if(!video_id) {
        video_id = document.getElementById('video_id').getAttribute('data-video-id');
    }
    var form = new FormData(),
        request = new XMLHttpRequest();
    form.append("video", video_id);
    request.open(
        "POST",
        "/video/fallback",
        true
    );
    request.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
    request.setRequestHeader('X-Requested-With', "XMLHttpRequest");
    request.onreadystatechange = function() {
        if (request.readyState == 4 && request.status == 200) {
            var response = JSON.parse(request.response);
        }
    };
    request.send(form);
}

window.onload = function init() {
    try {
        // webkit shim
        window.AudioContext = window.AudioContext || window.webkitAudioContext;
        navigator.getUserMedia = navigator.getUserMedia || navigator.webkitGetUserMedia;
        window.URL = window.URL || window.webkitURL;

        audio_context = new AudioContext;
        console.log('audio processor: navigator.getUserMedia ' + (navigator.getUserMedia ? 'available.' : 'not present!'));
    } catch (e) {
        alert('audio processor: no web audio support in this browser!');
    }

    navigator.getUserMedia({audio: true}, startUserMedia, function(e) {
        $("#control_wrapper").hide();
        $('#loader').hide();
        $("#video_blk").notify("Audio recording device error! Please, check your system  settings.", {
            autoHideDelay: 25000,
            elementPosition: "top center"
        });
        sendVideoStatusFallback();

        console.log('audio processor: no live audio input: ' + e);
    });
};