/**
 * Upload image to server.
 *
 * Author: www.5idraw.com
 */

function uploadImage(uploadRoot, uploadUrl, dataURL, oldName) {
	var date = new Date();

	var form  = document.createElement("form");
	var input = document.createElement("input");
	
	var year = date.getFullYear();
	var mon = date.getMonth() + 1;
	var day = date.getDate();
	var newName = null;
	var url = uploadUrl.replace("//", "/");
	var path = uploadRoot + year + "/" + mon + "/" + day + "/";

	if(oldName) {
		var slash = oldName.lastIndexOf("/");
		if(slash > 0) {
			oldName = oldName.substr(slash+1);
		}
	}
	else {
		oldName = "";
	}
	newName = "5idraw_" + date.getHours() + "_" + date.getMinutes() + "_" + date.getSeconds() + ".png";

	form.appendChild(input);
	form.setAttribute("action", url);
	form.setAttribute("method", "post");

	input.setAttribute("type",  "hidden");
	input.setAttribute("name",  "dataurl");
	input.setAttribute("value", dataURL);
	
	input = document.createElement("input");
	form.appendChild(input);
	input.setAttribute("type",  "hidden");
	input.setAttribute("name",  "filename");
	input.setAttribute("value", newName);
	
	input = document.createElement("input");
	form.appendChild(input);
	input.setAttribute("type",  "hidden");
	input.setAttribute("name",  "oldfilename");
	input.setAttribute("value", oldName);

	document.body.appendChild(form);
	form.appendChild(input);
	form.submit();
	form.removeChild(input);
	document.body.removeChild(form);

	var imgUrl = path + newName;

	imgUrl = imgUrl.replace("//", "/");
	imgUrl = imgUrl.replace("//", "/");

	return imgUrl;
}
