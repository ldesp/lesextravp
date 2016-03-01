/* enable strict mode */
"use strict";

var table1;
var listecell1; 
var savedRow;

var table2;
var listecell2;

var listelettres;
var lettresjointes;
var reference;
var jeuFini;
var grid_id;

var ncol2=13;

function changeFocus(table, listecell, ind, back)
{
    // index de la prochaine case
    var idx = back ? ind - 1 : ind + 1;
    idx = (idx < 0) ? 0 : idx; 
    idx = (idx > listecell.length - 1) ? listecell.length - 1 : idx;
    // changement du focus
    idx = listecell[idx];
    takeFocusK(table.rows[getRow(idx)].cells[getCol(idx)]); 
}; 

function updateTableCells(cellid1, cellid2, letter)
{  
    table1.rows[getRow(cellid1)].cells[getCol(cellid1)].childNodes[1].textContent = letter;
    table2.rows[getRow(cellid2)].cells[getCol(cellid2)].childNodes[1].textContent = letter;
};

function updateListLetter(ind, value)
{    
    listelettres[ind] = (value == "")  ? " " : value; 
    saveListLetters('listelettres', grid_id);
};

function updateLetter(letter, backFlag)
{
    if (jeuFini == true )
    {
        return;
    }

    if (isNotVisible(focusItem))
    {    
        return;
    }

    var ind1 = focusItem.firstChild.innerHTML - 1;
    var ind2 = focusItem.lastChild.innerHTML - 1;

    updateListLetter(ind1, letter);

    updateTableCells(listecell1[ind1], listecell2[ind2], listelettres[ind1]);

    compareLetters();

    // changement du focus
    if (focusItem.parentNode.parentNode.parentNode.id == 'table1')
    {
        changeFocus(table1, listecell1, ind1, backFlag );
    }
    else
    {
        changeFocus(table2, listecell2, ind2, backFlag );
    }
};

function takeFocusK(item)
{
    if (item.parentNode.parentNode.parentNode.id == 'table1')
    {
        var irow = getRow(listecell1[item.firstChild.innerHTML - 1]);          
        if (irow != savedRow){ 
            updateWithAnagram(table1.rows[irow].cells[0].textContent);
            savedRow = irow;
        }
    } 
    else
    {
        if (savedRow != -1){ 
            updateWithExtract();
            savedRow = -1;
        }
    }         
    takeFocus(item);
};
  
function getMaxLength(listemots)
{
    // calcul de la longueur max 
    var lmax = 0; 
    for ( var i = 0; i < listemots.length; i++ )
    {
        if( listemots[i].length > lmax)
        {
             lmax = listemots[i].length;
        }  
    }
    return lmax;
};

function getLetterCell(ind1, ind2)
{
   var html = "<td  class=\"td_letter\"  onClick=\"takeFocusK(this);\" >";
    html += "<div class=\"d_n1\">" + ind1 + "</div>";
    html += "<div class=\"d_l\"></div>"; ;
    html += "<div class=\"d_n2\">" + ind2 + "</div>";
    html += "</td>";
    return html;
};

function getContentTable1(listeM, situeL)
{
     // calcul de la longueur du mot le plus long
    var maxlength  = getMaxLength(listeM);
    // dimensionnement de la colonne de definition
    var html = "<colgroup><col width='120'/></colgroup>";
    // dimensionnement des autres colonnes
    for (var i = 1; i < maxlength + 2; i++)
    {    
        html += "<colgroup><col width='20'/></colgroup>";
    }
    // ajout des cases avec lettres et des cases noires
    var ind = 0;
    for (var i = 0; i < listeM.length; i++)
    {   
        html += "<tr>";
        html +=  "<td class=\"td_def\" >" + listeM[i] + "</td>";
        html +=  "<td class=\"td_index\" >" + alphabet.charAt(i) + "</td>";
        for (var j = 0; j < maxlength; j++)
        {   
            if (j < listeM[i].length)  
            {    
                html += getLetterCell(ind + 1, situeL[ind] + 1);
                ind++;
            }
            else
            {
                html += "<td class=\"td_empty\" ></td>";
            }
        }
        html += "</tr>";
    }
    return html;
};

function getContentTable2(listeL, situeL)
{
    // generation de la table inversee de situeL 
    var situelettre2 = [];
    for (var i  =0; i < situeL.length; i++)
    {
        situelettre2[situeL[i]] = i;       
    } 
    // dimensionnement des colonnes
    var html="";
    for (var i = 0; i < ncol2; i++)
    {    
        html += "<colgroup><col width='20'/></colgroup>";
    }
    // ajout des cases avec lettres et des cases noires
    var icol = 0;
    var ind = 0;  
    var i,j;
    for (i = 0; i < listeL.length; i++)
    { 
        for (j = 0; j < listeL[i] + 1; j++)
        {   
            if (icol == 0)
            {
                 html += "<tr>";
            } 
            if (j < listeL[i])  
            {    
                html += getLetterCell(situelettre2[ind] + 1, ind + 1);
                ind++;
            }
            else
            {
                html += "<td class=\"td_empty\" ></td>";
            }
            icol+=1;
            if (icol == ncol2)
            {
                html += "</tr>";
                icol = 0;
            }
        }
    }
    return html;
};

function fillListCell(table, listecell)
{
    var ind = 0;
    for (var irow = 0; irow < table.rows.length; irow++)
    {   
        var cells =  table.rows[irow].cells;
        for (var icol = 0; icol < cells.length; icol++)
        {   
            if (cells[icol].getAttribute('class') == 'td_letter')
            {
                 listecell[ind] = getCellId(irow, icol);
                 ind++;
            } 
        }
    } 
};

function saveLists()
{
    if(typeof window.localStorage == "undefined")
    {
        return;
    }
    window.localStorage.setItem('listelettres', listelettres.join());
};

function changeGrids()
{
    if (jeuFini == true )
    {
        return;
    }
    var node1 = document.getElementById('div_grid1');
    var node2 = document.getElementById('div_grid2');
    var button = document.getElementById('toggle');
    if (node1.style.display == 'none')
    {
        node2.style.display = 'none';
        node1.style.display = 'inline-block';
        if (savedRow != -1){ 
            updateWithAnagram(table1.rows[savedRow].cells[0].textContent);
        }
        button.value = 'extrait';
    }
    else
    {
        node1.style.display = 'none';
        node2.style.display = 'inline-block';
        updateWithExtract();
        button.value = 'anagrammes';
    }
}

function compareLetters()
{
    if (listelettres.join('') != lettresjointes )
    {
        return;
    }
    var node1 = document.getElementById('div_grid1');
    var node2 = document.getElementById('div_grid2');
    var button = document.getElementById('toggle');
    if (node2.style.display == 'none')
    {
        node1.style.display = 'none';
        node2.style.display = 'inline-block';
    }
    button.style.display = 'none';
    document.getElementById('pioche').style.display = 'none';
    jeuFini = true;
    writeMessage(" BRAVO!!! Vous avez trouvé <BR/> référence:" + reference, MessageType.MT_info, true); 
}
    
function initGrids()
{
    // recuperation des entrees
    var remoteinputs = document.getElementById('entrees').innerHTML.split('_');

    // recuperation des listes sauvegardees ou par defaut
    var situeL = remoteinputs[0].split(',').map(function(x){return parseInt(x, 10)});
    grid_id = remoteinputs[3];
    getListLetters(situeL.length, 'listelettres', grid_id);
    saveListLetters('listelettres', grid_id);

    // destruction des tables existantes
    table1 = document.getElementById("table1");
    if(table1 != null)
    {
        table1.parentNode.removeChild(table1);
    }
    table2 = document.getElementById("table2");
    if(table2 != null)
    {
        table2.parentNode.removeChild(table2);
    }

    // creation des cellules des grilles
    table1 = document.createElement('table');
    table2 = document.createElement('table');
    table1.id = 'table1';
    table2.id = 'table2';
    var listeM = remoteinputs[1].split(',');
    table1.innerHTML = getContentTable1(listeM, situeL);
    var listeL = remoteinputs[2].split(',').map(function(x){return parseInt(x, 10)});
    table2.innerHTML = getContentTable2(listeL, situeL);

    // remplissage des listes donnant la position des lettres dans les grilles
    listecell1 = [];
    listecell2 = [];
    fillListCell(table1, listecell1);
    fillListCell(table2, listecell2);

    // remplissage des grilles avec la liste de lettres sauvegardees
    for (var i = 0; i < situeL.length; i++)
    {  
        updateTableCells(listecell1[i], listecell2[situeL[i]], listelettres[i]);
    } 
    // rattachement des grilles au DOM
    document.getElementById('div_grid1').appendChild(table1);
    document.getElementById('div_grid2').appendChild(table2);
    document.getElementById('div_grid2').style.display = 'none';
    // creation du clavier
    document.getElementById('pioche').innerHTML = getKeyboard();
    // focus sur la premiere case de la premiere grille
    focusItem = table1.rows[getRow(listecell1[1])].cells[getCol(listecell1[1])];
    savedRow = 1;
    takeFocusK(table1.rows[getRow(listecell1[0])].cells[getCol(listecell1[0])]);
    
    // verification que le jeu n'est pas terminé
    jeuFini = false;
    lettresjointes = remoteinputs[4];
    reference = remoteinputs[5];
    compareLetters();  
};


