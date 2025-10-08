document.addEventListener("DOMContentLoaded", function(){
    const alerta = document.getElementById("alerta");
    if(alerta){
        setTimeout(() => {
            alerta.style.transition = "opacity 0.5s ease";
            alerta.style.opacity = "0";
        }, 5000);
    }
});