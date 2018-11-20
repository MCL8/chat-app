<div id="group_chat_dialog" title="Group Chat">
    <div id="group_chat_history">
    </div>
    <div class="form-group">
        <div class="chat_message_area">
            <div id="group_chat_message" contenteditable class="form-control">
            </div>
            <div class="image_upload">
                <form id="uploadImage" method="post" action="upload.php">
                    <label for="uploadFile"><img src="upload.png" /></label>
                    <input type="file" name="uploadFile" id="uploadFile" accept=".jpg, .png" />
                </form>
            </div>
        </div>
    </div>
    <div class="form-group" align="right">
        <button type="button" name="send_group_chat" id="send_group_chat" class="btn btn-primary">Send</button>
    </div>
</div>