
$(document).ready(function(){
	getUser();
	setInterval(function(){
        getGroupChatHistory();
        getUser();
        updateChatHistory();
		updateLastActivity();
	}, 1000);

    function getGroupChatHistory() {
        var group_chat_dialog_active = $('#is_active_group_chat_window').val();
        var action = "fetch_data";
        if(group_chat_dialog_active == 'yes')
        {
            $.ajax({
                url:"update/group_chat_history",
                method:"POST",
                data:{action:action},
                success:function(data) {
                    $('#group_chat_history').html(data);
                }
            });
        }
    }

    function getUser() {
        $.ajax({
            url:"update/get_user",
            method:"POST",
            success:function(data){
                $('#user_details').html(data);
            }
        });
    }

    function getUserChatHistory(to_user_id) {
        $.ajax({
            url:"update/chat_history",
            method:"POST",
            data:{to_user_id:to_user_id},
            success:function(data){
                $('#chat_history_' + to_user_id).html(data);
            }
        });
    }

    function updateChatHistory() {
        $('.chat_history').each(function(){
            var to_user_id = $(this).data('touserid');
            getUserChatHistory(to_user_id);
        });
    }

	function updateLastActivity() {
		$.ajax({
			url:"update/last_activity",
			success:function() {
			}
		});
	}

	function makeChatDialogBox(to_user_id, to_user_name) {
		var modal_content = `<div id="user_dialog_${to_user_id}" class="user_dialog" title="You have chat with ${to_user_name}">
			<div style="height:400px; border:1px solid #ccc; overflow-y: scroll; margin-bottom:24px; padding:16px;"
				class="chat_history" data-touserid="${to_user_id}" id="chat_history_${to_user_id}">
		    	${getUserChatHistory(to_user_id)};
			</div>
			<div class="form-group">
				<textarea name="chat_message_${to_user_id}" id="chat_message_${to_user_id}"
				class="form-control chat_message"></textarea>
			</div>
			<div class="form-group" align="right">
				<button type="button" name="send_chat" id="${to_user_id}" class="btn btn-primary send_chat">Send</button>
			</div>
		</div>`;
		$('#user_model_details').html(modal_content);
	}

	$(document).on('click', '.start_chat', function(){
		var to_user_id = $(this).data('touserid');
		var to_user_name = $(this).data('tousername');
		makeChatDialogBox(to_user_id, to_user_name);
		$("#user_dialog_" + to_user_id).dialog({
			autoOpen:false,
			width:400
		});
		$('#user_dialog_' + to_user_id).dialog('open');
	});

	$(document).on('click', '.send_chat', function(){
		var to_user_id = $(this).attr('id');
		var chat_message = $('#chat_message_' + to_user_id).val();
        if(chat_message != '') {
            $.ajax({
                url: "insert_message",
                method: "POST",
                data: {to_user_id: to_user_id, chat_message: chat_message},
                success: function () {
                }
            });
        }
	});

	$(document).on('click', '.ui-button-icon', function(){
		$('.user_dialog').dialog('destroy').remove();
		$('#is_active_group_chat_window').val('no');
	});

	$(document).on('focus', '.chat_message', function(){
		var is_type = 'yes';
		$.ajax({
			url:"update/is_type",
			method:"POST",
			data:{is_type:is_type},
			success:function() {
			}
		});
	});

	$(document).on('blur', '.chat_message', function(){
		var is_type = 'no';
		$.ajax({
			url:"update/is_type",
			method:"POST",
			data:{is_type:is_type},
			success:function() {
			}
		});
	});

	$('#group_chat_dialog').dialog({
		autoOpen:false,
		width:400
	});

	$('#group_chat').click(function(){
		$('#group_chat_dialog').dialog('open');
		$('#is_active_group_chat_window').val('yes');
		getGroupChatHistory();
	});

	$('#send_group_chat').click(function(){
		var chat_message = $('#group_chat_message').html();
		var action = 'insert_data';
		if(chat_message != '')
		{
			$.ajax({
				url:"insert_group_message",
				method:"POST",
				data:{chat_message:chat_message, action:action},
				success:function(data){
					$('#group_chat_message').html('');
					$('#group_chat_history').html(data);
				}
			});
		}
	});

	$('#uploadFile').on('change', function(){
		$('#uploadImage').ajaxSubmit({
			target: "#group_chat_message",
			resetForm: true
		});
	});
});