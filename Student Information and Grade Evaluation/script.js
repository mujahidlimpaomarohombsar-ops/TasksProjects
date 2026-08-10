function calculateAverage (scores) {
    
    let total = 0;
    for (let i = 0; i < scores.length; i++) {
        total += scores[i];
    }
    return total / scores.length;
    
}
    //submit button
    document.getElementById('submitBtn').addEventListener('click', function() {

    //student name

    let firstname = document.getElementById('fname').value;
    let lastname = document.getElementById('lname').value;

    // quiz (loops? )

    let quiz = [];

    quiz.push(parseFloat(document.getElementById('quiz1').value));
    quiz.push(parseFloat(document.getElementById('quiz2').value));
    quiz.push(parseFloat(document.getElementById('quiz3').value));

    //average
    
    let average = calculateAverage(quiz);

    //pass or fail

    let result = average >= 75 ? "Passed" : "Failed";
    let status = result + "(" + average.toFixed(2) + ")";

    // send to html

    document.getElementById('fname').value = firstname;
    document.getElementById('lname').value = lastname;
    document.getElementById('quiz1').value = quiz[0];
    document.getElementById('quiz2').value = quiz[1];
    document.getElementById('quiz3').value = quiz[2];
    document.getElementById('grade').textContent = status;

    // Multiplication Table (1-10)
    let tableNumber = quiz[0]; 
    if (!isNaN(tableNumber)) {
        console.log("Multiplication Table for " + tableNumber + ":");
        for (let i = 1; i <= 10; i++) {
            console.log(tableNumber + " x " + i + " = " + (tableNumber * i));
        }
    }

    let tableNumber1 = quiz[1]; 
    if (!isNaN(tableNumber1)) {
        console.log("Multiplication Table for " + tableNumber1 + ":");
        for (let i = 1; i <= 10; i++) {
            console.log(tableNumber1 + " x " + i + " = " + (tableNumber1 * i));
        }
    }

    let tableNumber2 = quiz[2]; 
    if (!isNaN(tableNumber2)) {
        console.log("Multiplication Table for " + tableNumber2 + ":");
        for (let i = 1; i <= 10; i++) {
            console.log(tableNumber2 + " x " + i + " = " + (tableNumber2 * i));
        }
    }
    });
    
    // reset (remove lang mga names sa values)

        document.getElementById('resetBtn').addEventListener('click', function() {

        document.getElementById('fname').value = '';
        document.getElementById('lname').value = '';
        document.getElementById('quiz1').value = '';
        document.getElementById('quiz2').value = '';
        document.getElementById('quiz3').value = '';
        document.getElementById('grade').textContent = '';
    });
