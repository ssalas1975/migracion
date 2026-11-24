if (document.querySelector("#ordenid") != null) {
    let value = document.querySelector("#ordenid").value;
    if (value === undefined || value === null || value === "") {
        var selectionCount = document.querySelectorAll(".wpb_gallerymodal").length;
        document.querySelector("#ordenid").value = selectionCount;
    }
}
