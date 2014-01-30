function pagination()
{

    this.output = new Array();
    this.nbtotal;
    this.nbmaxparpage = 10;
    this.nbdepages;

    this.minid;
    this.pageencours;

    this.init = function(nbtotal, nbmaxparpage, pageencours){

        this.nbtotal      = nbtotal;
        this.nbmaxparpage = nbmaxparpage;
        this.pageencours  = pageencours;
        this.nbdepages    = Math.ceil(this.nbtotal / this.nbmaxparpage);
        
    }

    this.Generate = function(){

        this.minid = ( this.pageencours - 1 ) * this.nbmaxparpage;
        
        index_arr = 0;

        if ( this.nbdepages > 1 )
        {
            for ( i=1; i <= this.nbdepages; i++ )
            {
            
                if ( i == this.pageencours )
                {
                    this.output[index_arr] = new Array();
                    this.output[index_arr]['link'] = false;
                    this.output[index_arr]['page'] = i;
                    
                    index_arr++;
                }
                else
                {
                    this.output[index_arr] = new Array();
                    this.output[index_arr]['link'] = true;
                    this.output[index_arr]['page'] = i;

                    index_arr++;
                }
            }
        }
        else
        {
            this.output = "NULL";
        }

    }
}