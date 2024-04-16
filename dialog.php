<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

<style>
    .fa-check-circle {
        background-color: #4CAF50;
        border-radius: 50%;
        padding: 10px;
        color: white;
    }
</style>

<div id="successDialog" class="modal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.4);">
    <div class="modal-content" style="background-color: #fefefe; margin: 20% auto; padding: 20px; border-radius: 10px; width: 300px; text-align: center;">
        <span class="close" onclick="closeDialog('successDialog')" style="color: #aaa; position: absolute; top: 10px; right: 10px; font-size: 24px; cursor: pointer;">&times;</span>
        <i class="material-icons" style="color: #4CAF50; font-size: 48px; margin-bottom: 20px;">check_circle</i>
        <h2>Success!</h2>
        <p>Your request was successful.</p>
        <button class="close-button" onclick="closeDialogAndRedirect('successDialog')" style="background-color: #4CAF50; color: white; border: none; padding: 10px 20px; text-align: center; text-decoration: none; display: inline-block; font-size: 16px; margin-top: 20px; cursor: pointer; border-radius: 5px;">Close</button>
    </div>
</div>

<div id="failureDialog" class="modal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.4);">
    <div class="modal-content" style="background-color: #fefefe; margin: 20% auto; padding: 20px; border-radius: 10px; width: 300px; text-align: center;">
        <span class="close" onclick="closeDialog('failureDialog')" style="color: #aaa; position: absolute; top: 10px; right: 10px; font-size: 24px; cursor: pointer;">&times;</span>
        <i class="material-icons" style="color: #FF5733; font-size: 48px; margin-bottom: 20px;">cancel</i>
        <h2 style="color: #FF0000;">Oops! Something went wrong.</h2>
        <p>We're sorry, but your request couldn't be processed successfully. Please try again later.</p>
        <button class="close-button" onclick="closeDialogAndRedirect('failureDialog')" style="background-color: #FF5733; color: white; border: none; padding: 10px 20px; text-align: center; text-decoration: none; display: inline-block; font-size: 16px; margin-top: 20px; cursor: pointer; border-radius: 5px;">Close</button>
    </div>
</div>

<script>
    function closeDialog(dialogId) {
        var dialog = document.getElementById(dialogId);
        dialog.style.display = "none";
    }

    function closeDialogAndRedirect(dialogId) {
        closeDialog(dialogId);
        var url = window.location.href.split('?')[0]; // Remove query string from URL
        window.history.replaceState({}, document.title, url); // Remove query parameters from URL without refreshing
    }

    // Check if the URL contains success or failure parameters
    var urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('success')) {
        openDialog('successDialog');
    } else if (urlParams.has('failure')) {
        openDialog('failureDialog');
    }

    function openDialog(dialogId) {
        var dialog = document.getElementById(dialogId);
        dialog.style.display = "block";
    }
</script>
