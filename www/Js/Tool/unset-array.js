function unset_array(array, valueOrIndex){

    var output=[];
    for(var i in array){
		if (i!=valueOrIndex)
	       output[i]=array[i];
	}
	return output;
	
}