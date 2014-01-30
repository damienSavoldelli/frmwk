/*
var arr_clients = new Array();
var current_page = 1;
var nb_pages = 0;

jQuery(document).ready(function() {
    
    jQuery('#search_client_input').bind('keyup', function()
    {
        if(jQuery(this).attr('value').length >= 3)
        {
            var nom = jQuery(this).attr('value');

            launch_search(nom, 1);
        }
    });

}); */

function launch_search(nom, page){

        jQuery.ajax({

                url:'ajx.client-search.php',
                dataTypeString:'json',
                type:'POST',
                data: "nom="+nom+"&p="+page,
                success: function(data) {

                    if(data!=false)
                    {
                        var total_client = data.list_client.length;
                        var html_list_client = "<table><thead><tr><th>Action</th><th>Nom</th><th>Adresse</th><th>Cp</th><th>Ville</th><th>Tel</th></tr></thead><tbody>";

                        total_result = data.total_result;
                        nb_pages = data.nb_pages;

                        for(cpt_client = 0;cpt_client < total_client; cpt_client++)
                        {
                            var client = data.list_client[cpt_client];
                            arr_clients[cpt_client]                        = new Array();
                            arr_clients[cpt_client]['id']                  = client.id;
                            arr_clients[cpt_client]['nom_commercial']      = client.nom_commercial;
                            arr_clients[cpt_client]['adresse']             = client.adresse;
                            arr_clients[cpt_client]['cp']                  = client.cp;
                            arr_clients[cpt_client]['ville']               = client.ville;
                            arr_clients[cpt_client]['adresse_facturation'] = client.adresse_facturation;
                            arr_clients[cpt_client]['cp_facturation']      = client.cp_facturation;
                            arr_clients[cpt_client]['ville_facturation']   = client.ville_facturation;
                            arr_clients[cpt_client]['tel_1']               = client.tel_1;
                            arr_clients[cpt_client]['tel_2']               = client.tel_2;
                            arr_clients[cpt_client]['tel_3']               = client.tel_3;
                            arr_clients[cpt_client]['tel_4']               = client.tel_4;
                            arr_clients[cpt_client]['tel_5']               = client.tel_5;
                            arr_clients[cpt_client]['fax_1']               = client.fax_1;
                            arr_clients[cpt_client]['fax_2']               = client.fax_2;
                            arr_clients[cpt_client]['fax_3']               = client.fax_3;
                            arr_clients[cpt_client]['fax_4']               = client.fax_4;
                            arr_clients[cpt_client]['fax_5']               = client.fax_5;
                            arr_clients[cpt_client]['email_1']             = client.email_1;
                            arr_clients[cpt_client]['email_2']             = client.email_2;
                            arr_clients[cpt_client]['email_3']             = client.email_3;
                            arr_clients[cpt_client]['email_4']             = client.email_4;
                            arr_clients[cpt_client]['email_5']             = client.email_5;
                            arr_clients[cpt_client]['contact_principal']   = client.contact_principal;
                            arr_clients[cpt_client]['contact_referent']    = client.contact_referent;
                            arr_clients[cpt_client]['date_insert']         = client.date_insert;

                            html_list_client += "<tr><td><button id='get-client-"+cpt_client+"' class='bt-get-client icon'><img alt='Box Incoming' src='img/icons/buttons/box_Incoming.png'></button></td><td>"+client.nom_commercial+"</td><td>"+client.adresse+"</td><td>"+client.cp+"</td><td>"+client.ville+"</td><td>"+client.tel_1+"</td></tr>";
                        }
                        html_list_client += "</tbody></table><div class='paging_full_numbers'><span>";

                        // Affichage + ou - 1 page
                        //-------------------------

                        //affichage du - si on est pas sur la première
                        if(current_page!=1)
                        {
                            html_list_client += " <span class='paginate_button' id='moins'> - </span> ";
                        }

                        // Affichage + ou - 10 pages
                        //-------------------------
                        if(current_page>10)
                        {
                            html_list_client += " <span class='paginate_button' id='moins_10'> -10 </span> ";
                        }

                        // Affichage raccourcis
                        //-------------------------

                        if(current_page>6 && nb_pages>10) // première page
                        {
                            html_list_client += " <span class='page paginate_button' id='page-1'> 1 ...</span> ";
                        }


                        if(nb_pages!=1)
                        {
                            var obj_pagination = new pagination();
                            
                            obj_pagination.init(total_result, 3, current_page);
                            
                            obj_pagination.Generate();
                            
                            for(var i= 0; i < obj_pagination.output.length; i++)
                            {
                                var class_page = " paginate_button";
                                //document.write(obj_pagination.output[i]);
                                if(!obj_pagination.output[i]['link'])
                                {
                                    // current_page
                                    class_page = " paginate_active"
                                }
    
                                if(nb_pages>10)
                                {
                                    if((obj_pagination.output[i]['page']>current_page-4 || current_page<=6)
                                    && (obj_pagination.output[i]['page']<current_page+4 || current_page>=(nb_pages-5)))
                                    {
                                        html_list_client += " <span class='page"+class_page+"' id='page-"+obj_pagination.output[i]['page']+"'> "+obj_pagination.output[i]['page']+" </span> ";
    
                                    }
                                }
                                else
                                {
                                    html_list_client += " <span class='page"+class_page+"' id='page-"+obj_pagination.output[i]['page']+"'> "+obj_pagination.output[i]['page']+" </span> ";
                                }
                            }
                            
                            if(current_page<(nb_pages-5)) // dernière page
                            {
                                html_list_client += " <span class='page paginate_button' id='page-"+nb_pages+"'> ... "+nb_pages+"</span> ";
                            }
    
                            if(current_page<=nb_pages-10)
                            {
                                html_list_client += " <span class='paginate_button' id='plus_10'> +10 </span> ";
                            }
    
                            //affichage du + si on est pas sur la dernière page
                            if(current_page!=nb_pages)
                            {
                                html_list_client += " <span class='paginate_button' id='plus'> + </span> ";
                            }  
                        }
                        
                        html_list_client += "</span></div>";
                        
                        jQuery('#result_client_search').html(html_list_client);

                        jQuery('#plus').unbind('click');
                        jQuery('#plus').bind('click', function(){

                            current_page++;
                            launch_search(nom, current_page);

                        });
                        
                        jQuery('#moins').unbind('click');
                        jQuery('#moins').bind('click', function(){

                            current_page--;
                            launch_search(nom, current_page);

                        });
                        
                        jQuery('#plus_10').unbind('click');
                        jQuery('#plus_10').bind('click', function(){

                            current_page += 10;alert(current_page)
                            launch_search(nom, current_page);

                        });

                        jQuery('#moins_10').unbind('click');
                        jQuery('#moins_10').bind('click', function(){

                            current_page -= 10;
                            launch_search(nom, current_page);

                        });


                        jQuery('.page').unbind('click');
                        jQuery('.page').bind('click', function(){

                            var expression   = new RegExp('page-','');
                            current_page     =  parseInt(jQuery(this).attr("id").replace(expression, ''));

                            launch_search(nom, current_page);

                        });

                        jQuery('.bt-get-client').unbind('click');
                        jQuery('.bt-get-client').bind('click', function(){

                            var expression  = new RegExp('get-client-','');
                            var line_id     =  parseInt(jQuery(this).attr("id").replace(expression, ''));

                            jQuery(".client-tel").addClass("hidden");
                            jQuery(".client-fax").addClass("hidden");
                            jQuery(".client-email").addClass("hidden");

                            jQuery("#client-nom-commercial").html(arr_clients[line_id]['nom_commercial']);
                            jQuery("#client-adresse").html(arr_clients[line_id]['adresse']);
                            jQuery("#client-cp").html(arr_clients[line_id]['cp']);
                            jQuery("#client-ville").html(arr_clients[line_id]['ville']);
                            jQuery("#client-cp").html(arr_clients[line_id]['cp']);
                            jQuery("#client-adresse-facturation").html(arr_clients[line_id]['adresse_facturation']);
                            jQuery("#client-cp-facturation").html(arr_clients[line_id]['cp_facturation']);
                            jQuery("#client-ville-facturation").html(arr_clients[line_id]['ville_facturation']);
                            jQuery("#client-tel-1").html(arr_clients[line_id]['tel_1']);
                            if(arr_clients[line_id]['tel_1']!="") jQuery("#client-tel-1").removeClass('hidden');
                            jQuery("#client-tel-2").html(arr_clients[line_id]['tel_2']);
                            if(arr_clients[line_id]['tel_2']!="") jQuery("#client-tel-2").removeClass('hidden');
                            jQuery("#client-tel-3").html(arr_clients[line_id]['tel_3']);
                            if(arr_clients[line_id]['tel_3']!="") jQuery("#client-tel-3").removeClass('hidden');
                            jQuery("#client-tel-4").html(arr_clients[line_id]['tel_4']);
                            if(arr_clients[line_id]['tel_4']!="") jQuery("#client-tel-4").removeClass('hidden');
                            jQuery("#client-tel-5").html(arr_clients[line_id]['tel_5']);
                            if(arr_clients[line_id]['tel_5']!="") jQuery("#client-tel-5").removeClass('hidden');
                            jQuery("#client-fax-1").html(arr_clients[line_id]['fax_1']);
                            if(arr_clients[line_id]['fax_1']!="") jQuery("#client-fax-1").removeClass('hidden');
                            jQuery("#client-fax-2").html(arr_clients[line_id]['fax_2']);
                            if(arr_clients[line_id]['fax_2']!="") jQuery("#client-fax-2").removeClass('hidden');
                            jQuery("#client-fax-3").html(arr_clients[line_id]['fax_3']);
                            if(arr_clients[line_id]['fax_3']!="") jQuery("#client-fax-3").removeClass('hidden');
                            jQuery("#client-fax-4").html(arr_clients[line_id]['fax_4']);
                            if(arr_clients[line_id]['fax_4']!="") jQuery("#client-fax-4").removeClass('hidden');
                            jQuery("#client-fax-5").html(arr_clients[line_id]['fax_5']);
                            if(arr_clients[line_id]['fax_5']!="") jQuery("#client-fax-5").removeClass('hidden');
                            jQuery("#client-email-1").html(arr_clients[line_id]['email_1']);
                            if(arr_clients[line_id]['email_1']!="") jQuery("#client-email-1").removeClass('hidden');
                            jQuery("#client-email-2").html(arr_clients[line_id]['email_2']);
                            if(arr_clients[line_id]['email_2']!="") jQuery("#client-email-2").removeClass('hidden');
                            jQuery("#client-email-3").html(arr_clients[line_id]['email_3']);
                            if(arr_clients[line_id]['email_3']!="") jQuery("#client-email-3").removeClass('hidden');
                            jQuery("#client-email-4").html(arr_clients[line_id]['email_4']);
                            if(arr_clients[line_id]['email_4']!="") jQuery("#client-email-4").removeClass('hidden');
                            jQuery("#client-email-5").html(arr_clients[line_id]['email_5']);
                            if(arr_clients[line_id]['email_5']!="") jQuery("#client-email-5").removeClass('hidden');

                            jQuery("#client-contact-principal").html(arr_clients[line_id]['contact_principal']);
                            jQuery("#client-contact-referent").html(arr_clients[line_id]['contact_referent']);

                            jQuery("#id_client").attr("value", arr_clients[line_id]['id']);
                            jQuery("#nom_commercial").attr("value", arr_clients[line_id]['nom_commercial']);
                            
                            return false;
                        });

                    }
                    else
                    {
                        jQuery('#result_client_search').html('Aucun résultat');
                    }
                }
            });

    }
    
    
/*

    <input type="hidden" name="id_client" id="id_client" value="{id_client}" />
    <input type="hidden" name="nom_commercial" id="nom_commercial" value="{nom_commercial}" />
    <input type="text" id="search_client_input" value="" autocomplete="off"/>

    <p class="message">{message}</p>

    <h2>Client</h2>

    <div>
        <label>Nom commercial: </label><label id="client-nom-commercial">{nom_commercial}</label>
        <label>Adresse: </label><label id="client-adresse">{adresse}</label>
        <label>Cp: </label><label id="client-cp">{cp}</label>
        <label>Ville: </label><label id="client-ville">{ville}</label>
        <label>Adresse de facturation: </label><label id="client-adresse-facturation">{adresse_facturation}</label>
        <label>Cp de facturation: </label><label id="client-cp-facturation">{cp_facturation}</label>
        <label>Ville de facturation: </label><label id="client-ville-facturation">{ville_facturation}</label>

        <div>
            <label>Téléphone: </label>
            <label id="client-tel-1" class="hidden client-tel">{tel_1}</label>
            <label id="client-tel-2" class="hidden client-tel">{tel_2}</label>
            <label id="client-tel-3" class="hidden client-tel">{tel_3}</label>
            <label id="client-tel-4" class="hidden client-tel">{tel_4}</label>
            <label id="client-tel-5" class="hidden client-tel">{tel_5}</label>
        </div>
        <div>
            <label>Fax: </label>
            <label id="client-fax-1" class="hidden client-fax">{fax_1}</label>
            <label id="client-fax-2" class="hidden client-fax">{fax_2}</label>
            <label id="client-fax-3" class="hidden client-fax">{fax_3}</label>
            <label id="client-fax-4" class="hidden client-fax">{fax_4}</label>
            <label id="client-fax-5" class="hidden client-fax">{fax_5}</label>
        </div>
        <div>
            <label>Email: </label>
            <label id="client-email-1" class="hidden client-email">{email_1}</label>
            <label id="client-email-2" class="hidden client-email">{email_2}</label>
            <label id="client-email-3" class="hidden client-email">{email_3}</label>
            <label id="client-email-4" class="hidden client-email">{email_4}</label>
            <label id="client-email-5" class="hidden client-email">{email_5}</label>
        </div>

        <label>Contact principal: </label><label id="client-contact-principal">{contact_principal}</label>
        <label>Contact référent: </label><label id="client-contact-referent">{contact_referent}</label>

    </div>

    <div id="result_client_search">
    </div>
*/