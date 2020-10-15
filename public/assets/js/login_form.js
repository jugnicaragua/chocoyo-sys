const firebaseErrors = {
    'auth/user-not-found': 'No existe un usuario con ese identificador',
    'auth/email-already-in-use': 'La direccion de correo ya está en uso',
};

/**
 * Handles the sign in button press.
 */
function toggleSignIn() {
    if ( firebase.auth().currentUser ) {
        // [START signout]
        firebase.auth().signOut();
        // [END signout]/declaration
    } else {
        var email = document.getElementById('inputEmail').value;
        var password = document.getElementById('inputPassword').value;
        if (email.length < 4) {
            alert('Por favor ingrese una direccion de correo electrónica.');
            return;
        }
        if (password.length < 4) {
            alert('Por favor ingrese el password.');
            return;
        }

        // Sign in with email and pass.
        // [START authwithemail]
        firebase.auth().signInWithEmailAndPassword(email, password)
            .then( function (firebaseuser ) {
                $('#').submit();
            })
            .catch(function(error) {
            // Handle Errors here.
            var errorCode = error.code;
            var errorMessage = error.message;
            // [START_EXCLUDE]
            $('#box-alert').bsAlert({
                type: 'danger',
                content: firebaseErrors[error.code] || errorMessage,
                dismissible: true,
                position: 'after'
            });
            console.log(error);
            // [END_EXCLUDE]
        });
        // [END authwithemail]
    }
    //todo manage error button
}

function sendPasswordReset() {
    var email = document.getElementById('email').value;
    // [START sendpasswordemail]
    firebase.auth().sendPasswordResetEmail(email).then(function() {
        // Password Reset Email Sent!
        // [START_EXCLUDE]
        alert('Se envió correo para restablecer clave!');
        // [END_EXCLUDE]
    }).catch(function(error) {
        // Handle Errors here.
        var errorCode = error.code;
        var errorMessage = error.message;
        // [START_EXCLUDE]
        if (errorCode == 'auth/invalid-email') {
            alert(errorMessage);
        } else if (errorCode == 'auth/user-not-found') {
            alert(errorMessage);
        }
        console.log(error);
        // [END_EXCLUDE]
    });
    // [END sendpasswordemail];
}
