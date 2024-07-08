// fetchcoursedata.js

function selectBatch() {
    var batch = document.getElementById("batchSelect").value;
    $.ajax({
        url: "showStudent.php",

        method: "POST",
        data: { id: batch },
        success: function (data) {
            $("#courseTableBody").html(data);
        }
    });
}
