            var MenuItems = document.querySelector("#MenuItems");

            MenuItems.style.maxHeight = "0px";
                    
            function menutoggle(){

                if(MenuItems.style.maxHeight == "Opx") 
                {
                    MenuItems.style.maxHeight = "200px";
                }
                else
                {
                    MenuItems.style.maxHeight = "Opx";
                }
            }

var ProductImg = document.getElementById("productImg");
            var SmallImg = document.getElementsByClassName("small-img");

            SmallImg[0].onclick = function() {
                ProductImg.src = SmallImg[0].src;
            }
            SmallImg[1].onclick = function() {
                ProductImg.src = SmallImg[1].src;
            }
            SmallImg[2].onclick = function() {
                ProductImg.src = SmallImg[2].src;
            }


            function register(){
                document.getElementById("RegisterForm").style.transform = "translateX(0px)";
                document.getElementById("LoginForm").style.transform = "translateX(0px)";
                indicator = document.getElementById("indicator").style.transform = "translateX(100px)";
            }

            function login(){
                document.getElementById("RegisterForm").style.transform = "translateX(300px)";
                document.getElementById("LoginForm").style.transform = "translateX(300px)";
                indicator = document.getElementById("indicator").style.transform = "translateX(0px)";
            }