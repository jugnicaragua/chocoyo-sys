// Your web app's Firebase configuration
var firebaseConfig = {
    apiKey: "AIzaSyBqltT-GVNPGdoMqsia_Fi4KpvBLyw_GWU",
    authDomain: "declaraciones-8daea.firebaseapp.com",
    databaseURL: "https://declaraciones-8daea.firebaseio.com",
    projectId: "declaraciones-8daea",
    storageBucket: "declaraciones-8daea.appspot.com",
    messagingSenderId: "504928142812",
    appId: "1:504928142812:web:06d5fc57af4a055d367b19",
    measurementId: "G-T5NFLYKZL0"
};

// Initialize Firebase with a "default" Firebase project
// Initialize Firebase
firebase.initializeApp(firebaseConfig);
//var defaultProject = firebase.initializeApp(firebaseConfig);

// Google OAuth Client ID, needed to support One-tap sign-up.
// Set to null if One-tap sign-up is not supported.
var CLIENT_GO_ID = 'YOUR_OAUTH_CLIENT_ID';
