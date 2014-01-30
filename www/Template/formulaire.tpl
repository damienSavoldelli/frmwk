<!-- 
Classes de controle javascript sur les inputs
    la première est utilisé comme le nom du champs =>  ex: vous_devez_accepter_les_cgu
    required => chamsp requis pour la validation du form
    max => caractères max, récupère la valeur de maxlength="25" pour le nombre
    min => caractères min, récupère la _x_ juste après pour le nombre
    inf => doit être un nombre inférieur à _x_ situé juste après
    sup => doit être un nombre supérieur à _x_ situé juste après
 -->

    <a href="index.php"><span class="glyphicon glyphicon-chevron-left"></span> Retour</a>
    <h1>Formulaire</h1>
	<form action="" method="POST" role="form" class="form-horizontal">
        <div id="civilite_FORMGROUP" class="form-group {civilite_CLASS}">
    		<label class="col-xs-2 control-label"> Civilité : *</label> <br>                                                                                       
    		<label for="rad_mme" class="checkbox-inline">Mme
    		    <input id="rad_mme" type="radio" name="civilite" value="mme"  {mme_civilite_CHECKED} /> 
            </label>
    	    <label for="rad_mlle" class="checkbox-inline">Mlle
                <input id="rad_mlle" type="radio" name="civilite" value="mlle" {mlle_civilite_CHECKED} />
            </label>    	    
    	    <label for="rad_m" class="checkbox-inline">M
    	       <input id="rad_m" type="radio" name="civilite" value="m" {m_civilite_CHECKED} />
            </label>
            <div id="error_civilite" class="alert" style="{civilite_ERROR_STYLE}">{civilite_ERROR}</div>
        </div>

        <div id="date_FORMGROUP" class="form-group row {date_CLASS}">
            <label  class="col-xs-2 control-label"> Je suis né(e) le : * </label> 
            <div class="col-xs-2">
                <select class="form-control" id="slct_jour" name="jour">
                    <!-- BEGIN liste_j -->
                    <option value="{jour}" {selected}>{jour}</option>
                    <!-- END liste_j -->                        
                </select>
            </div>
            <div class="col-xs-2">
                <select class="form-control" id="slct_mois" name="mois">
                    <!-- BEGIN liste_m -->
                    <option value="{mois}" {selected}>{mois_lbl}</option>
                    <!-- END liste_m -->                        
                </select>
            </div>
            <div class="col-xs-2">
                <select class="form-control" id="slct_annee" name="annee">
                    <!-- BEGIN liste_a -->
                    <option value="{annee}" {selected}>{annee}</option>
                    <!-- END liste_a -->                        
                </select>
            </div>
            <div class="col-xs-2">
                <select class="form-control" id="slct_heure" name="heure">
                    <!-- BEGIN liste_h -->
                    <option value="{heure}" {selected}>{heure}</option>
                    <!-- END liste_h -->                        
                </select>
            </div>
            <div class="col-xs-2">
                <select class="form-control" id="slct_minutes" name="minutes">
                    <!-- BEGIN liste_mn -->
                    <option value="{minutes}" {selected}>{minutes}</option>
                    <!-- END liste_mn -->                        
                </select>
            </div>
            <div id="error_date" class="alert" style="{date_ERROR_STYLE}">{date_ERROR}</div>
        </div>
        <div id="prenom_FORMGROUP" class="form-group {prenom_CLASS}"> 
            <label for="inp_prenom" class="control-label">Votre prénom : * </label>
            <input id="inp_prenom" class="prénom required max min _2_ form-control" name="prenom" type="text" maxlength="25" value="{prenom_VALUE}"/> 
            <div id="error_prenom" class="alert" style="{prenom_ERROR_STYLE}">{prenom_ERROR}</div>                           
        </div>
        <div id="age_FORMGROUP" class="form-group {age_CLASS}">                          
            <label for="inp_age" class="control-label">Votre age : *</label>
            <input id="inp_age" class="age required max min _2_ sup _17_ inf _78_ form-control" name="age" min="18" max="77" type="number" maxlength="2" value="{prenom_VALUE}"/>
            <div id="error_age" class="alert" style="{age_ERROR_STYLE}">{age_ERROR}</div>                            
        </div>        
        <div id="portable_FORMGROUP" class="form-group {portable_CLASS}">         
            <label for="inp_portable" class="control-label">Votre n° de portable (facultatif): (ex: 0611223344) {portable_ERROR}</label> 
            <input id="inp_portable" class="numéro_de_téléphone_portable mobile required max min _10_ form-control" name="portable" maxlength="20" value="{portable_VALUE}" placeholder="0611223344" /> 
            <div id="error_portable" class="alert" style="{portable_ERROR_STYLE}">{portable_ERROR}</div>
        </div>
        <div id="email_FORMGROUP" class="form-group {email_CLASS}">                   
            <label for="inp_email" class="control-label">Votre e-mail : *</label>
            <input id="inp_email" class="adresse_email required email max min _4_ form-control" name="email" type="email" value="{email_VALUE}" maxlength="40" placeholder="email@email.com" />
            <div id="error_email" class="alert" style="{email_ERROR_STYLE}">{email_ERROR}</div>
        </div>
        <div id="password_FORMGROUP" class="form-group {password_CLASS}">
            <label for="password" class="control-label">Votre mot de passe</label> 
            <input id="inp_password" class="mot_de_passe required max min _6_ form-control" name="password" type="password" maxlength="15" value="">
            <label for="password_conf" class="control-label">Confirmer votre mot de passe</label> 
            <input id="inp_password_conf" class="confirmation_de_votre_mot_de_passe required max min _4_ form-control" name="password_conf" type="password" maxlength="15" value="">
            <input name="md5" id="md5" type="hidden" value="" />
            <div id="error_password" class="alert" style="{password_ERROR_STYLE}">{password_ERROR}</div>
            <div id="error_password_conf" class="alert" style="{password_conf_ERROR_STYLE}">{password_conf_ERROR}</div>
        </div>

        <div id="accord_FORMGROUP" class="form-group {accord_CLASS}">
            {accord_ERROR}
            <input type="checkbox" id="chkb_accord" name="accord" checked="checked">
            <label for="chkb_accord" class="control-label">Je souhaite que mes informations restent confidentielles.</label>
		</div>
        <div id="conditions_FORMGROUP" class="form-group {conditions_CLASS}">
            {conditions_ERROR}
            <input type="checkbox" class="vous_devez_accepter_les_cgu required" id="chkb_conditions" name="conditions" > 
            <label for="chkb_conditions" class="control-label">J'accepte les cgu.</label>
        </div>        
        <input class="btn btn-primary" id="btn_submit" type="submit" value="Valider" name="submit_form"  >
	</form>
    <a href="index.php"><span class="glyphicon glyphicon-chevron-left"></span> Retour</a>