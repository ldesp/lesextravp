/* enable strict mode */
"use strict";


var nrow;
var ncol;

function checkInputs(citation, colonne1, show)
{   
    var cita = citation.monFiltre().replace(/[\s]+/g,"").toUpperCase();
    var colo = colonne1.monFiltre().replace(/[\s]+/g,"").toUpperCase();   
    if ( cita.length > 260 ) 
    {
       writeMessage(" L'extrait est trop long, plus de 260 lettres ", MessageType.MT_error, show);   
       return false;
    }
    if ( cita.length < 52 ) 
    {
       writeMessage(" L'extrait est trop court, moins de 52 lettres ", MessageType.MT_error, show);   
       return false;
    }
    // ajout de la citation dans la pioche      
    clearLetterCounters();
    addLettersToCounters(cita);

    if (colo.length > alphabet.length) 
    {
        writeMessage(" L'indice est trop long, plus de 26 lettres ", MessageType.MT_error, show);
        return false;
    }
    if ((colo.length > cita.length/5) || (colo.length > alphabet.length)) 
    {
        writeMessage(" Les mots à decouvrir seront trop courts, moins de 6 lettres. Enlever des lettres à l'indice ", MessageType.MT_error, show);   
        return false;
    }
    if ((colo.length < cita.length/10)) 
    {
        writeMessage(" Les mots à decouvrir seront trop longs, plus de 10 lettres. Rajouter des lettres à l'indice ", MessageType.MT_error, show);   
        return false;
    }
    // retrait des lettres de la premiere colonne 
    var missing = '';
    for (var i = 0; i < colo.length; i++)
    {
        missing += subLetterToCounters(colo.charAt(i));
    }  
    if (missing.length > 0) 
    {
        writeMessage(" Les lettres suivantes "+ missing + " ne sont pas disponibles dans l'extrait", MessageType.MT_error, show); 
        return false;            
    };

    nrow = colo.length;
    ncol = Math.ceil((cita.length - colo.length) / colo.length);

    return true;
};

function getInputs()
{ 
    var formus = document.getElementsByTagName("form");
    if (formus.length == 0)
    {
        return;
    }
    // recuperation des saisies 
    var citations =  document.getElementsByClassName("cita1");
    if (citations.length == 0)
    {
        return;
    }
    var colonnes = document.getElementsByClassName("colo1");
    if (colonnes.length == 0)
    {
        return;
    }
    var descriptions = document.getElementsByClassName("desc1");
    if (descriptions.length == 0)
    {
        return;
    }
    var references = document.getElementsByClassName("refe1");
    if (references.length == 0)
    {
        return;
    }
    var pioches = document.getElementsByClassName("pioc1");
    if (pioches.length == 0)
    {
        return;
    }
    // verification des saisies: extrait et premiere colonne
    var cita1 = citations[0].value.monFiltre().replace(/[\s]+/g, "").toUpperCase();
    var colo1 = colonnes[0].value.monFiltre().replace(/[\s]+/g, "").toUpperCase();       
    if (!checkInputs(cita1, colo1, true))
    {
        return;
    }
    // verification des saisies: description et reference et auteur
    if (descriptions[0].value.monFiltre().replace(/[\s]+/g, "").length < 10) 
    {
        writeMessage(" La description est peu détaillée, moins de 10 lettres", MessageType.MT_error, true);   
        return;
    }
    // verification des saisies: description et reference et auteur
    if (descriptions[0].value.monFiltre().replace(/[\s]+/g, "").length > 65) 
    {
        writeMessage(" La description est trop détaillée, plus de 65 lettres", MessageType.MT_error, true);   
        return;
    }
    if (references[0].value.monFiltre().replace(/[\s]+/g, "").length < 10) 
    {
        writeMessage(" Les références sont peu detaillées, moins de 10 lettres", MessageType.MT_error, true);   
        return;
    }
    if (references[0].value.monFiltre().replace(/[\s]+/g, "").length > 260) 
    {
        writeMessage(" Les références sont trop detaillees, plus de 260 lettres", MessageType.MT_error, true);   
        return;
    }
    cita1 = citations[0].value.monFiltre().replace(/[\s]+/g, ",").toUpperCase();
    pioches[0].value = colo1 + "_" + dumpLetterCounters().replace(/[\s]+/g, "") + '_' + cita1;
    writeMessage(" L'extrait proposé peut se transformer en une grille " + nrow + " X " + (ncol + 1), MessageType.MT_info, true); 
    formus[0].submit();   
    return;
};

