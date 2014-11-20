

function WebDropUser(id)
{
	this.element=null;
	this.id=id;
	
}

WebDropUser.prototype.getElement=function() {
	if(!this.element) {
		this.element=document.createElement('div');
		this.element.manager=this;
		
		this.element.setAttribute('data-id', this.id);
		this.element.className='webDropUser';
		this.element.addEventListener("dragenter", this.dragEnter, false);
		this.element.addEventListener("dragover", this.dragOver, false);
		this.element.addEventListener("drop", this.drop, false);
	}
	return this.element
}

WebDropUser.prototype.dragEnter=function(e) {
	e.stopPropagation();
	e.preventDefault();
}

WebDropUser.prototype.dragOver=function(e) {
	e.stopPropagation();
	e.preventDefault();
}

WebDropUser.prototype.drop=function(e) {

	e.stopPropagation();
	e.preventDefault();
	var dt = e.dataTransfer;
	var files = dt.files;

	this.manager.sendFiles(files);
}


WebDropUser.prototype.sendFiles=function(files) {

	console.debug(files);

	for (var i = 0; i < files.length; i++) {
		console.debug(this.id);
		//new FileUpload(files[i]);
	}
}














