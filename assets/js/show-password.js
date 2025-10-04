//Función para alternar la contraseña
  const togglePassword = document.getElementById('togglePassword');
  const passwordField = document.getElementById('passwordField');
  const eyeIcon = document.getElementById('eyeIcon');

  togglePassword.addEventListener('click', function(e){
    // Alternar el tipo del campo de la contraseña entre "password" y "text"
    const type = passwordField.type === 'password' ? 'text' : 'password';
    passwordField.type = type;
    // Alternar el icono del ojo
    eyeIcon.classList.toggle('fa-eye-slash');
  });