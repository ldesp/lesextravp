/* enable strict mode */
"use strict";

var COL_OFFSET = 3;

var listelettres;
var grid_id;

var nrow;
var ncol;

var table1;

function listeMots()
{
    var liste ='';
    for (var i = 0; i < nrow; i++) 
    {   
        for (var j = COL_OFFSET - 1; j < COL_OFFSET + ncol; j++) 
        {
            var letter = table1.rows[i].cells[j].childNodes[1].textContent;
            if (letter != " ")
            {
                    liste += letter;
            }                  
        }
        if ( i < nrow-1 )
        {
            liste+=','
        }
    }
    return liste;
};

function checkResult()
{    
    var ind = 0;  
    var index ='';
    for (var i = 0; i < nrow; i++) 
    {   
        // verification pas de blanc dans les colonnes intermediaires
        for (var j = 0; j < ncol - 1; j++) 
        {
            if (listelettres[ind] == " ")
            {
                index = alphabet.charAt(i);
            }
            ind++;
        }
        ind++; // derniere lettre du mot
    }
    if (index.length > 0)
    {
        writeMessage(" le mot de l'index " + index + " est incomplet ou trop court", MessageType.MT_error, true); 
    }
    else
    {
        // preparation de l'envoi de la liste de mots
        var mots = document.getElementsByClassName("mots1");
        if (mots.length == 0)
        {
            return;
        }
        mots[0].value = listeMots();
        // activation de l'envoi
        document.getElementById('div_mots').style.display = 'block';
    } 
};

function getIndex(id)
{
    var ind = ncol * getRow(id);
    ind += (getCol(id) - COL_OFFSET); 
    return ind; 
};

function changeFocus(id, back)
{
    var ic = getCol(id);
    var ir = getRow(id);
    ic = back ? ic-1 : ic+1;
 
    if ((ic == ncol + COL_OFFSET) && (ir != nrow - 1))
    {
        ic = COL_OFFSET;
        ir = ir + 1;
    }
    else if ((ic == ncol + COL_OFFSET) && (ir == nrow - 1))
    {
        ic = ncol + COL_OFFSET - 1;
    }

    if ((ic == COL_OFFSET - 1) && (ir != 0))
    {
        ic = ncol + COL_OFFSET - 1;
        ir = ir - 1;
    }
    else if ((ic == COL_OFFSET - 1) && (ir == 0)) 
    {
        ic = COL_OFFSET;
    }    
    takeFocus(table1.rows[ir].cells[ic]); 
}; 

function updateListLetter(ind, value)
{
    var letter = (value == "") ? " " : value; 
    // verif si lettre identique
    if ( letter == listelettres[ind] )
    {
         return;
    }
    // verif si lettre presente dans la pioche
    var missing = subLetterToCounters(letter);
    if ( missing.length > 0)
    {
        writeMessage(" la lettre "+ missing + " n'est pas disponible", MessageType.MT_error, true); 
        return;
    }
    // remise en pioche de la lettre remplacee
    if ( listelettres[ind] != " ")
    {
        addLettersToCounters(listelettres[ind]);
    } 
    // sauvegarde de la nouvelle lettre       
    listelettres[ind] = letter;
    saveListLetters('listelettresw', grid_id);
};

function updateLetter(letter, backFlag)
{ 
    if (isNotVisible(focusItem))
    {    
        return;
    }

    var id = focusItem.id.split('_')[1];
    var ind = getIndex(id);

    updateListLetter(ind, letter);

    table1.rows[getRow(id)].cells[getCol(id)].childNodes[1].textContent = listelettres[ind];

    // mise à jour de la pioche
    updateKeyboard();

    // changement du focus
    changeFocus(id, backFlag);

    // verifier si toutes les lettres ont ete utilisees
    var count = countLetterCounters();
    if (count == 0)
    {
        checkResult();
    }
    else if (count == 1)
    { 
         // desactivation de l'envoi 
         document.getElementById('div_mots').style.display = 'none';
    }  
};

function getTableContent(colonne1)
{
    var colo = colonne1.monFiltre().replace(/[\s]+/g,"").toUpperCase();
    // dimensionnement de la colonne de definition
    var html = "<colgroup><col width='120'/></colgroup>";
    // dimensionnement des autres colonnes
    for (var i=1; i<ncol+COL_OFFSET; i++)
    {    
        html += "<colgroup><col width='20'/></colgroup>";
    }
 
    for (var i = 0; i < nrow; i++)
    {   
        html += "<tr>";
        html +=  "<td class=\"td_def\" >" + "</td>";
        html +=  "<td class=\"td_index\" >" + alphabet.charAt(i) + "</td>";

        html += "<td  class=\"td_letter\" >";  
        html += "<div class=\"d_n1\"></div>";         
        html += "<div class=\"d_l\">" + colo.charAt(i) + "</div>"; 
        html += "<div class=\"d_n2\"></div>";  
        html += "</td>";
     
        for (var j = 0; j < ncol;  j++)
        {   
            html += "<td  class=\"td_letter\"  onClick=\"takeFocus(this);\" >";  
            html += "<div class=\"d_n1\"></div>";               
            html += "<div class=\"d_l\"></div>"; 
            html += "<div class=\"d_n2\"></div>";   
            html += "</td>";
        }
        html += "</tr>";
    }
    return html;
};

function initCounters(colo, pioche)
{   

    // construction de la pioche      
    clearLetterCounters();
    addLettersToCounters(pioche);
    // dimension de la grille
    nrow = colo.length;
    ncol = Math.ceil(pioche.length / colo.length);
    // representation de la pioche sur le clavier virtuel
    document.getElementById('pioche').innerHTML = getKeyboard();
    updateKeyboard()

};

function updatePage(colonne1)
{
    // create table 
    table1 = document.getElementById("table1");
    if(table1 != null)
    {
        table1.parentNode.removeChild(table1);
    }
    table1 = document.createElement('table');
    table1.id = 'table1';
    table1.innerHTML = getTableContent(colonne1);
    // add letters
    var ind = 0;
    for (var irow = 0; irow < nrow; irow++)
    {
        for (var icol = COL_OFFSET; icol < ncol + COL_OFFSET; icol++)
        {
             var node = table1.rows[irow].cells[icol];
             node.childNodes[1].textContent = listelettres[ind];
             node.id = 't_' + getCellId(irow, icol)
             ind++;
        }
    }     
    document.getElementById('div_grid1').appendChild(table1);
    // desactivation de l'envoi
    document.getElementById('div_mots').style.display = 'none';
    // mise a jour de la pioche
    updateKeyboard();
    if (countLetterCounters() == 0)
    {
        checkResult();
    }
    // focus sur la premiere case 
    focusItem = table1.rows[0].cells[COL_OFFSET+1];
    takeFocus(table1.rows[0].cells[COL_OFFSET]);  
};

function initGrid()
{
    // recuperation des entrees
    var remoteinputs = document.getElementById('entrees').innerHTML.split('_');
    // init de la pioche
    initCounters(remoteinputs[0], remoteinputs[1])
    // init  des lettres placees
    grid_id = remoteinputs[2];
    getListLetters(nrow * ncol, 'listelettresw', grid_id);
    for (var i = 0; i < listelettres.length; i++)
    {
        var missing = subLetterToCounters(listelettres[i]);
        if (missing.length > 0)
        {
            listelettres[i] = " ";
        }
    } 
    saveListLetters('listelettresw', grid_id);
    // mise à jour de la page html 
    updatePage(remoteinputs[0]); 
};


