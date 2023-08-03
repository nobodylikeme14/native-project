$(document).ready(function() {
    // Load the JSON data using jQuery AJAX
    $.getJSON('assets/peserta.json', function(data) {
        // Data is loaded successfully, and you can access it inside this function
        // Now you can use the "data" variable to perform the lucky draw
        // For example, you can store it in a global variable for further use
        window.peserta = data;
    }).fail(function() {
        alert('Failed to load participant data.');
    });

    function performLuckyDraw() {
        if (!window.peserta || window.peserta.length === 0) {
          alert('No participant data available.');
          return;
        }
      
        // Randomly shuffle the peserta array
        for (let i = window.peserta.length - 1; i > 0; i--) {
          const j = Math.floor(Math.random() * (i + 1));
          [window.peserta[i], window.peserta[j]] = [window.peserta[j], window.peserta[i]];
        }
      
        // Display participant names one by one rapidly
        const resultElement = $("#result");
        const animationInterval = 10; // Animation interval in milliseconds for randomizing participant names
        const participantCount = window.peserta.length;
      
        let currentIndex = 0;
        let animationTimer;
        let countdownTimer; // Declare the countdownTimer outside the function scope
      
        // Function to display participant names rapidly
        function displayParticipantNames() {
          if (currentIndex >= participantCount) {
            currentIndex = 0; // Reset the index to keep the randomization going
          }
          resultElement.find('#randomize-peserta').text("Randomizing... " + window.peserta[currentIndex].name);
          currentIndex++;
        }
      
        // Function to announce the winner and start the fireworks animation
        function announceWinner() {
          clearInterval(animationTimer); // Stop the randomization animation
          clearInterval(countdownTimer); // Stop the countdown timer
      
          // Get a random index to pick a participant from the list
          const randomIndex = Math.floor(Math.random() * participantCount);
          // Get the lucky winner from the peserta list
          const luckyWinner = window.peserta[randomIndex];
      
          // Display the lucky winner's name and number with fadeIn effect
          resultElement.fadeOut(300, function() {
            resultElement.find('#show-winner').text("Name: " + luckyWinner.name + " | Number: " + luckyWinner.number);
            resultElement.find('#randomize-peserta').empty();
            $('#exampleModal').find('.winner-name').text(luckyWinner.name);
            $('#exampleModal').find('.winner-number').text(luckyWinner.number);
            resultElement.fadeIn(300, function() {
              // Trigger the fireworks animation after the winner is announced
              pauseBackgroundSound();
              $('body').fireworks();
              $('#exampleModal').modal('show');
              $('#startButton').prop('disabled', false);
            });
          });
        }
      
        // Start the countdown for 10 seconds
        resultElement.find('#show-winner').text("Get ready! The lucky draw will start in 10 seconds...");
        const countdownDuration = 10000; // Countdown duration in milliseconds (10 seconds)
        const countdownInterval = 1000; // Countdown interval in milliseconds (1 second)
        let countdownTime = countdownDuration;
      
        countdownTimer = setInterval(function () {
          countdownTime -= countdownInterval;
          if (countdownTime <= 0) {
            clearInterval(countdownTimer);
            announceWinner(); // Start the randomization animation after the countdown is over
          } else {
            resultElement.find('#show-winner').text("Get ready! The lucky draw will start in " + (countdownTime / 1000) + " seconds...");
          }
        }, countdownInterval);
      
        // Start the randomization animation
        animationTimer = setInterval(displayParticipantNames, animationInterval);
      }
      

    // Bind the lucky draw function to the "Start Lucky Draw" button click event
    $("#startButton").click(function() {
        $('#startButton').prop('disabled', true);
        playBackgroundSound();
        performLuckyDraw();
    });

    $('#exampleModal').on('hidden.bs.modal', function(event) {
        $('body').fireworks('destroy');
    });

    function playBackgroundSound() {
        const backgroundSound = document.getElementById('backgroundSound');
        backgroundSound.currentTime = 0;
        backgroundSound.play();
    }
    function pauseBackgroundSound() {
        const backgroundSound = document.getElementById('backgroundSound');
        const winnerSound = document.getElementById('winnerSound');
        backgroundSound.pause();
        winnerSound.play();
      }
});