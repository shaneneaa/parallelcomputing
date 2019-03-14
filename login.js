$(document).ready(()=>{
    
        $("#login").on("submit", function(){
        
        var email = $('#email').val();
        var password = $('#password').val();
    
            $.post("./main.php",
            {
              
               email: email,
               password: password
        
            },data=>{
                localStorage.setItem('token',data);
            }); 
        
          });
    })
    
    
    