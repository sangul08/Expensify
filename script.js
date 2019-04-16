$(document).ready( function () {
    $('#login-form').submit( function (event) {
        var formdata = $(this).serialize();
        $.ajax({
            type: "POST",
            data: formdata,
            url: "login.php",
            dataType: 'json',
         }).done(function(e){
                if(e.response === 200){
                    setTimeout(function(){
                        location.reload(); // then reload the page.(3)
                    }, 500);
                } else if(e.response === 401){
                    alert(e.message);
                } else if(e.response === 404){
                    alert("Account not found!");
                } else {
                    alert("Login unsuccessful. Invalid credentials!");
                }
        });

         event.preventDefault();
    });
    $('#createTransaction-form').submit( function (event) {
        const formdata = $(this).serialize();
        const date = new Date($(this).find("input")[0].value);
        var empty = false;
        $('input[type="text"]', '#createTransaction-form').each(function(){
            if($(this).val()==""){
                empty = true;
                return true;
            }
        });
        if(empty == true){
            alert("Please fill all the fields!");
        }
        else if(!(/^\d{4}-\d{2}-\d{2}$/).test($(this).find("input")[0].value) || (date instanceof Date && isNaN(date.valueOf()))){
            alert("Please enter date in YYYY-MM-DD format.")
            $(this).find("input")[0].focus();
        }
        else if(isNaN(parseFloat($(this).find("input")[2].value)) || parseInt($(this).find("input")[2].value) == 0){
            alert("The amount should be a valid number");
            $(this).find("input")[2].focus();
        }else{
            $.ajax({
                type: "POST",
                data: formdata,
                url: "login.php",
                dataType: 'json',
            }).done(function(e){
                console.log(e);
                if(e.response === 200) {
                    alert("Transaction successfully added!");
                    setTimeout(function () {
                        location.reload(); // then reload the page.(3)
                    }, 200);
                } else if(e.response === 407){
                    alert("Please enter all the information or login again");
                } else{
                    alert("Create transaction unsuccessful!");
                }
            });
        }

        event.preventDefault();
    });
});