<?php require_once('_inc/conexao.php'); ?>
<?php
if(!isset($_SESSION['logado']))
echo "<script>self.location='../?p=login'</script>";
?>
    
    <link rel="stylesheet" href="chat/style.css" type="text/css" />
    
    <script type="text/javascript" src="../_js/jquery.min.js"></script>
    <script type="text/javascript" src="chat/chat.js"></script>
    <script type="text/javascript">
    
        // ask user for name with popup prompt    
        //var name = prompt("Enter your chat name:", "<?php echo $_SESSION['username']; ?>");
        
        // default name is 'Guest'
    	if (!name || name === ' ') {
    	   name = "<?php echo $_SESSION['username']; ?>";	
    	}
    	
    	// strip tags
    	name = name.replace(/(<([^>]+)>)/ig,"");
    	
    	// display name on page
    	$("#name-area").html("Você está: <span>" + name + "</span>");
    	
    	// kick off chat
        var chat =  new Chat();
    	$(function() {
    	
    		 chat.getState(); 
    		 
    		 // watch textarea for key presses
             $("#sendie").keydown(function(event) {  
             
                 var key = event.which;  
           
                 //all keys including return.  
                 if (key >= 33) {
                   
                     var maxLength = $(this).attr("maxlength");  
                     var length = this.value.length;  
                     
                     // don't allow new content if length is maxed out
                     if (length >= maxLength) {  
                         event.preventDefault();  
                     }  
                  }  
    		 																																																});
    		 // watch textarea for release of key press
    		 $('#sendie').keyup(function(e) {	
    		 					 
    			  if (e.keyCode == 13) { 
    			  
                    var text = $(this).val();
    				var maxLength = $(this).attr("maxlength");  
                    var length = text.length; 
                     
                    // send 
                    if (length <= maxLength + 1) { 
                     
    			        chat.send(text, name);	
    			        $(this).val("");
    			        
                    } else {
                    
    					$(this).val(text.substring(0, maxLength));
    					
    				}	
    				
    				
    			  }
             });
            
    	});
    </script>

</head>
<div class="box_top">Chat Global</div>
<div class="box_middle">
<body onload="setInterval('chat.update()', 1000)">

    <div id="page-wrap">
    
       
        
        <p id="name-area"></p>
        
        <div id="chat-wrap"><div id="chat-area"></div></div>
        
        <form id="send-message-area">
            <p>Sua mensagem: </p>
            <textarea id="sendie" maxlength = '100' ></textarea>
        </form>
    
    </div></div>
</div>
<div class="box_bottom"></div>
