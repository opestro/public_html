importScripts('https://www.gstatic.com/firebasejs/8.3.2/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/8.3.2/firebase-messaging.js');

firebase.initializeApp({
    apiKey: "AIzaSyD8eBYoyxMH8k_f7Njw8w3HegmLiMFeah4",
    authDomain: "nichen-397921.firebaseapp.com",
    projectId: "nichen-397921",
    storageBucket: "nichen-397921.appspot.com",
    messagingSenderId: "135987346899",
    appId: "1:135987346899:web:f8a63d35a564feafb08326",
    measurementId: "G-PY9WV67R97"
});

const messaging = firebase.messaging();

messaging.onBackgroundMessage(function(payload) {
    const notificationTitle = payload.notification.title;
    const notificationOptions = {
        body: payload.notification.body,
        icon: payload.notification.icon,
        image: payload.notification.image
    };

    self.registration.showNotification(notificationTitle, notificationOptions);
});