



function Block(width, height, depth, x, y, z)
{
	this.width=width;
	this.height=height;
	this.depth=depth;

	this.x=x;
	this.y=y;
	this.z=z;

	this.rotationX=0;
	this.rotationY=0;
	this.rotationZ=0;


	this.container=document.createElement('div');
	this.container.className='block';

	this.container.style.left=this.x+'px';
	this.container.style.top=this.y+'px';
	this.container.transform('translateZ('+this.z+'px)');


	this.faces={};

	this.faces['front']=document.createElement('div');
	this.faces['front'].className='front face';
	this.faces['front'].style.height=this.height+'px';
	this.faces['front'].style.width=this.width+'px';
	this.faces['front'].style.top=this.height*-1+'px';

	this.faces['front'].innerHTML='FRONT';

	this.container.appendChild(this.faces['front']);


	this.faces['back']=document.createElement('div');
	this.faces['back'].className='back face';
	this.faces['back'].style.height=this.height+'px';
	this.faces['back'].style.width=this.width+'px';
	this.faces['back'].style.top=this.height*-1+'px';

	this.faces['back'].transform('translateZ('+this.depth*-1+'px)');

	this.container.appendChild(this.faces['back']);


	this.faces['top']=document.createElement('div');
	this.faces['top'].className='top face';
	this.faces['top'].style.height=this.depth+'px';
	this.faces['top'].style.width=this.width+'px';
	this.faces['top'].style.top=this.height*-1+'px';
	this.faces['top'].transform('translateZ('+this.depth*-1+'px) rotateX(90deg)');



	this.faces['top'].innerHTML='TOP';

	this.container.appendChild(this.faces['top']);


	this.faces['left']=document.createElement('div');
	this.faces['left'].className='left face';
	this.faces['left'].style.height=this.height+'px';
	this.faces['left'].style.top=this.height*-1+'px';
	this.faces['left'].style.width=this.depth+'px';
	this.faces['left'].transform('translateZ('+this.depth*-1+'px) rotateY(-90deg)');

	this.faces['left'].innerHTML='LEFT';
	this.container.appendChild(this.faces['left']);



	this.faces['right']=document.createElement('div');
	this.faces['right'].className='right face';
	this.faces['right'].style.height=this.height+'px';
	this.faces['right'].style.width=this.depth+'px';
	this.faces['right'].style.left=this.width+'px';
	this.faces['right'].style.top=this.height*-1+'px';
	this.faces['right'].transform('rotateY(90deg)');

	this.faces['right'].innerHTML='RIGHT';
	this.container.appendChild(this.faces['right']);


	this.faces['bottom']=document.createElement('div');
	this.faces['bottom'].className='bottom face';
	this.faces['bottom'].style.height=this.depth+'px';
	this.faces['bottom'].style.width=this.width+'px';
	this.faces['bottom'].transform('translateZ('+this.depth*-1+'px) rotateX(90deg)');

	this.faces['bottom'].innerHTML='BOTTOM';

	this.container.appendChild(this.faces['bottom']);




	/*



	*/
}

Block.prototype.render=function(container) {
	jQuery(container).append(this.container);
}



Block.prototype.rotate=function(x, y, z) {
	this.rotationX=x;
	this.rotationY=y;
	this.rotationZ=z;

	this.container.transform(
		'translateZ('+this.z+'px)'+
		'rotateX('+this.rotationX+'deg)'+
		'rotateY('+this.rotationY+'deg)'+
		'rotateZ('+this.rotationZ+'deg)'
	);
}

Block.prototype.rotateX=function(x) {
	this.rotationX=x;
	this.container.transform(
		'translateZ('+this.z+'px)'+
			'rotateX('+this.rotationX+'deg)'+
			'rotateY('+this.rotationY+'deg)'+
			'rotateZ('+this.rotationZ+'deg)'
	);
}

Block.prototype.rotateY=function(y) {
	this.rotationY=y;
	this.container.transform(
		'translateZ('+this.z+'px)'+
			'rotateX('+this.rotationX+'deg)'+
			'rotateY('+this.rotationY+'deg)'+
			'rotateZ('+this.rotationZ+'deg)'
	);
}

Block.prototype.rotateZ=function(z) {
	this.rotationZ=z;
	this.container.transform(
		'translateZ('+this.z+'px)'+
			'rotateX('+this.rotationX+'deg)'+
			'rotateY('+this.rotationY+'deg)'+
			'rotateZ('+this.rotationZ+'deg)'
	);
}
