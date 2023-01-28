function toggleForm() {
	var editdiv = document.getElementById('editdiv');
	if (editdiv.style.height > "0px") {
        		editdiv.style.height = "0px";
            	editdiv.style.marginBottom = "0px";
		document.getElementById('toggle').style.display = 'block';
            	document.getElementById('cancel').style.display = 'none';
	} else {
		var trueHeight = editdiv.scrollHeight + 10;
        editdiv.style.height = trueHeight + "px";
       	editdiv.style.marginBottom = "10px";
    	document.getElementById('toggle').style.display = 'none';
        document.getElementById('cancel').style.display = 'block';
        var contentArea = document.getElementById('content');
        contentArea.setSelectionRange(0, 0);
        contentArea.focus();
        var main = document.getElementById('main');
        main.scrollIntoView();
            
    	rect = main.getBoundingClientRect();
    	recttop = rect.top;
    	window.scrollTo(0, recttop+100);
	}
}

function toggleComments(ID) {
    var repliesdiv = 'replies' + ID;
    var replies = document.getElementById(repliesdiv);
    replies.style.transition = "all .5s";
    if (replies.style.display != "none") {
        replies.style.display = "none";
    } else {
        var repliesdivs = document.getElementsByClassName('replies');
        for (var i = 0; i < repliesdivs.length; i++) {
            repliesdivs[i].style.display = "none";
        }
        console.log(ID);
        replies.style.display = "block";
        replies.style.marginTop = "5px";
        replies.style.marginBottom = "30px";
        replies.style.padding = "0px 15px 0px";
        var name = 'name' + ID;
        var namefield = document.getElementById(name);
        namefield.setSelectionRange(0, 0);
        namefield.focus();
        replies.scrollIntoView();
    }
}