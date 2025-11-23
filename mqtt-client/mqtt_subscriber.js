// mqtt_subscriber.js - Suscriptor MQTT en Node.js
const mqtt = require('mqtt');
const axios = require('axios');

// Conectar al broker MQTT local en el puerto 1883
const client = mqtt.connect('mqtt://localhost:1883');

client.on('connect', () => {
  console.log('Conectado al broker MQTT');
  // Suscribirse al tópico deseado (ejemplo: "sensor/temperatura")
  client.subscribe('sensor/temperatura', (err) => {
    if (!err) {
      console.log('Suscripción exitosa al tópico');
    }
  });
});

// Evento que se ejecuta al recibir un mensaje en el tópico suscrito
client.on('message', (topic, message) => {
  const mensaje = message.toString();  // Convertir Buffer a string
  console.log(`Mensaje recibido en "${topic}": ${mensaje}`);
  // Enviar los datos a la API de Laravel mediante POST
  axios.post('http://127.0.0.1:8000/api/mqtt-data', {
    topic: topic,
    value: mensaje
  })
  .then(res => {
    console.log('Datos enviados a Laravel, respuesta:', res.status);
  })
  .catch(err => {
    console.error('Error al enviar datos a Laravel:', err);
  });
});
