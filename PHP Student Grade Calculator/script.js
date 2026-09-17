document.getElementById("gradeForm").addEventListener("submit", function(event) {

    let grade1 = Number(document.getElementById("grade1").value);
    let grade2 = Number(document.getElementById("grade2").value);
    let grade3 = Number(document.getElementById("grade3").value);

    if (
        grade1 < 0 || grade1 > 100 ||
        grade2 < 0 || grade2 > 100 ||
        grade3 < 0 || grade3 > 100
    ) {
        alert("Please enter grades between 0 and 100.");
        event.preventDefault();
        return;
    }

});