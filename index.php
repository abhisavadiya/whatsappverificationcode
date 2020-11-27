<html>
    <head>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script src="https://sdk.accountkit.com/en_US/sdk.js"></script>
        <script>    
          // initialize Account Kit with CSRF protection
          AccountKit_OnInteractive = function(){
            AccountKit.init(
              {
                appId:"Write your app id here", 
                state:"Write your state here", 
                version:"v1.0",
                fbAppEventsEnabled:true 
              }
            );
          };
            
          function loginCallback(response) {
            //
            if (response.status === "PARTIALLY_AUTHENTICATED") {
              var code = response.code;
              var csrf = response.state;                
                $.post("verify.php", { code : code, csrf : csrf }, function(result){
                    console.log(result);
                });
                
            }
            else if (response.status === "NOT_AUTHENTICATED") {
              // handle authentication failure
               console.log("Fail in authicate");
            }
            else if (response.status === "BAD_PARAMS") {
              // handle bad parameters
                console.log("something in wrong");
            }
          }
            
            
          // phone form submission handler
          function smsLogin() {
            var countryCode = "+91";
            console.log(countryCode);
            var phoneNumber = document.getElementById("phone_number").value;
            AccountKit.login(
              'PHONE', 
              {countryCode: countryCode, phoneNumber: phoneNumber}, // will use default values if not specified
              loginCallback
            );
          }
    
        </script>
        

    </head>
    <body>
        <div>
            <center>
                <br>
                <input placeholder="phone number" id="phone_number"/>
                <button onclick="smsLogin();">Login via SMS</button>
            </center>
        </div>
    </body>
</html>
