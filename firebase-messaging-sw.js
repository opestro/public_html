importScripts('https://www.gstatic.com/firebasejs/9.14.0/firebase-app-compat.js')
importScripts('https://www.gstatic.com/firebasejs/9.14.0/firebase-messaging-compat.js')

var firebaseConfig ={
    apiKey: "AIzaSyD8eBYoyxMH8k_f7Njw8w3HegmLiMFeah4",
    authDomain: "nichen-397921.firebaseapp.com",
    projectId: "nichen-397921",
    storageBucket: "nichen-397921.appspot.com",
    messagingSenderId: "135987346899",
    appId: "1:135987346899:web:f8a63d35a564feafb08326",
    measurementId: "G-PY9WV67R97"
};

const app = firebase.initializeApp(firebaseConfig);
const messaging = firebase.messaging();

messaging.onBackgroundMessage(function(payload) {
    const notificationTitle = payload.data.title
    const notificationOptions = {
        body: payload.data.body,
        icon: payload.data.icon,
        image: payload.data.image
        }
        self.registration.showNotification(notificationTitle,notificationOptions);
})