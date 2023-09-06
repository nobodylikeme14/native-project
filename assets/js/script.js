$(document).ready(function() {
    // Load the JSON data using jQuery AJAX
    window.peserta = [
        {
          "name": "Rahmat Surya",
          "number": "1234567890"
        },
        {
          "name": "Dian Kusuma",
          "number": "9876543210"
        },
        {
          "name": "Irfan Pratama",
          "number": "4567890123"
        },
        {
          "name": "Alya Putri",
          "number": "5551112233"
        },
        {
          "name": "Budi Santoso",
          "number": "9998887766"
        },
        {
          "name": "Rina Agustina",
          "number": "4443332211"
        },
        {
          "name": "Joko Susanto",
          "number": "7776665544"
        },
        {
          "name": "Putri Sari",
          "number": "2220009988"
        },
        {
          "name": "Wahyu Pratama",
          "number": "6665554433"
        },
        {
          "name": "Siti Aisyah",
          "number": "1112223344"
        },
        {
          "name": "Rudi Hermawan",
          "number": "9992224455"
        },
        {
          "name": "Siti Nurhayati",
          "number": "5557779900"
        },
        {
          "name": "Rizal Triana",
          "number": "1114449900"
        },
        {
          "name": "Amelia Wijaya",
          "number": "8885550011"
        },
        {
          "name": "Andi Saputra",
          "number": "3338880022"
        },
        {
          "name": "Ratna Sari",
          "number": "2221115544"
        }
    ];

    // Bind the lucky draw function to the "Start Lucky Draw" button click event
    $("#startButton").click(function() {
        // Load the list of previous lucky draw winners from sessionStorage
        let previousWinners = JSON.parse(sessionStorage.getItem('previousWinners'));
        // If it doesn't exist, initialize an empty array
        if (!previousWinners) {
            previousWinners = [];
        }
        if (previousWinners.length >= window.peserta.length) {
            alert('All participants have already won.');
            return; // Don't proceed with the lucky draw
        }
        $('#startButton').prop('disabled', true);
        playBackgroundSound();
        performLuckyDraw(previousWinners);
    });

    function performLuckyDraw(previousWinners) {

        // Filter participants to exclude previous winners
        var filteredData = window.peserta.filter(participant => {
            return !previousWinners.some(winner => winner.name == participant.name && winner.number == participant.number);
        });

        if (!window.peserta || window.peserta.length == 0) {
            alert('No participant data available.');
            return;
        }

        // Randomly shuffle the peserta array
        for (let i = filteredData.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [filteredData[i], filteredData[j]] = [filteredData[j], filteredData[i]];
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

            // Get a random index to pick a participant from the filteredData list
            const randomIndex = Math.floor(Math.random() * filteredData.length);
            // Get the lucky winner from the filteredData list
            const luckyWinner = filteredData[randomIndex];

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
            previousWinners.push(luckyWinner);
            sessionStorage.setItem('previousWinners', JSON.stringify(previousWinners));
        }

        // Start the countdown for 10 seconds
        resultElement.find('#show-winner').text("Get ready! The lucky draw will start in 3 seconds...");
        const countdownDuration = 3000; // Countdown duration in milliseconds (10 seconds)
        const countdownInterval = 1000; // Countdown interval in milliseconds (1 second)
        let countdownTime = countdownDuration;

        countdownTimer = setInterval(function() {
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

    $('#exampleModal').on('hidden.bs.modal', function(event) {
        $('body').fireworks('destroy');
    });

    $("#resetLuckyDraw").click(function() {
        // Clear the previousWinners from sessionStorage
        sessionStorage.removeItem('previousWinners');
        // Refresh the page
        location.reload();
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