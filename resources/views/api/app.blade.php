<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>App Information Card</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
</head>
<body>
<style type="text/css">
    @media(max-width: 767px){
        .appIcon{
            width: 120px;
            height: 120px;
        }
    }
</style>
<div class="container py-5">
    <div class="row my-2">
        <form action="" method="post" id="formURL">
            @csrf
            <div class="form-group">
                <label>Android</label>
                <input type="url" name="android" id="android" class="form-control">
            </div>
            <div class="form-group">
                <label>IOS</label>
                <input type="url" name="ios" id="ios" class="form-control">
            </div>
            <div class="form-group">
                <label>Image1</label>
                <input type="url" name="images[]" id="images[]" class="form-control">
            </div>
            <div class="form-group">
                <label>Image2</label>
                <input type="url" name="images[]" id="images[]" class="form-control">
            </div>
            <div class="form-group">
                <label>Image3</label>
                <input type="url" name="images[]" id="images[]" class="form-control">
            </div>
            <div class="form-group">
                <label>Image4</label>
                <input type="url" name="images[]" id="images[]" class="form-control">
            </div>
            <div class="form-group my-2">
                <button class="btn btn-primary btnSubmit" type="submit">Submit</button>
                <button id="copyToClipboardButton" type="button" class="btn btn-success" style="display: none;">Copy to Clipboard</button>
            </div>
        </form>
    </div>
    <div class="result"></div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
$(document).ready(function() {
    $("#formURL").submit(function(e){
        e.preventDefault();
        $("#copyToClipboardButton").hide();
        $(".result").html("");
        var $btnSubmit = $('.btnSubmit');
        var originalText = $btnSubmit.text();

        // Change button text to spinner
        $btnSubmit.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...').attr('disabled', true);

        var params = $(this).serialize();
        $.ajax({
            url: "{{ route('app.scraper.post') }}", // The route that returns HTML content
            method: 'POST',
            data: params,
            success: function(response) {
                if (response.success === false) {
                    $('.result').html('<div class="alert alert-danger">'+response.message+'</div>');
                } else {
                    $('.result').html(response);
                    $("#copyToClipboardButton").fadeIn();
                }
                $btnSubmit.html(originalText).attr('disabled', false);
            },
            error: function(xhr, status, error) {
                // Handle errors
                $('.result').html("Error loading content");
                // Revert the button back to its original state
                $btnSubmit.html(originalText).attr('disabled', false);
            }
        });
    });
});
$(document).ready(function() {
    $('#copyToClipboardButton').on('click', function() {
        var appContentDiv = $('.result');

        if (appContentDiv.length) {
            var htmlContent = appContentDiv.html();
            var tempTextarea = $('<textarea>');

            $('body').append(tempTextarea);
            tempTextarea.val(htmlContent).select();
            document.execCommand('copy');
            tempTextarea.remove();

            // alert('Content copied to clipboard!');
        } else {
            alert('No content available to copy.');
        }
    });
});
</script>
</body>
</html>
