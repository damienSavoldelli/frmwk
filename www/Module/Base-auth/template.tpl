<br>
<span style="display:{HIDDEN_ERRORCONNEXION}" class="alert alert-danger">{labelERRORCONNEXION}</span>
<br>
<br>

<form action="{LINK_ACTION}" id="authForm" method="POST" role="form">
    <div class="form-group">
        <!-- BEGIN LABEL_TYPE_OUTSIDE -->
        <label for="email">{labelEMAIL}</label>
        <!-- END LABEL_TYPE_OUTSIDE -->
        <input type="text" id="email" class="form-control" name="email" value="{EMAIL}" {PLACEHOLDER_EMAIL} required>
    </div>
    <div class="form-group">
        <!-- BEGIN LABEL_TYPE_OUTSIDE -->
        <label for="password">{labelPASSWD}</label>
        <!-- END LABEL_TYPE_OUTSIDE -->
        <input type="password" id="password" class="form-control" name="password" value="{PASSWORD}" {PLACEHOLDER_PASSWORD} required>
    </div>

    <input name="md5" id="md5" type="hidden" value="" />
    <input type="hidden" name="connexion" value="1">

    <input type="submit" id="btConnex" class="btn btn-primary" name="btConnex" value="{labelSIGNIN}">
    
    <a href="{linkFORGETPASSWD}">{labelFORGETPASSWD}</a>
    <a href="{linkSIGNUP}">{labelSIGNUP}</a>
</form>
<!-- BEGIN IF_PLACEHOLDER -->
<script type="text/javascript">
jQuery(document).ready(function() {
    jQuery('#email').bind('focus', function() {
        if(jQuery(this).attr('value') == "{labelEMAIL}") jQuery(this).attr('value', "");
    });

    jQuery('#password').bind('focus', function() {
        if(jQuery(this).attr('value') == "{labelPASSWD}") jQuery(this).attr('value', "");
    });
});
</script>
<!-- END IF_PLACEHOLDER -->