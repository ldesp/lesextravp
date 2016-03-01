/* enable strict mode */
"use strict";
/* ************************************************* */
String.prototype.monFiltre = function() {
    return this.replace(/[ùûü]/g,"u")
               .replace(/[îï]/g,"i")
               .replace(/[àâä]/g,"a")
               .replace(/[ôö]/g,"o")
               .replace(/[éèêë]/g,"e")
               .replace(/ç/g,"c")
               .replace(/[^a-zA-Z]/g," ");
};
var alphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";

/* ************************************************* */
var letterCounters = { 
    "A":0, "B":0, "C":0, "D":0, "E":0, "F":0, "G":0, "H":0, "I":0, "J":0, "K":0, "L":0, "M":0,
    "N":0, "O":0, "P":0, "Q":0, "R":0, "S":0, "T":0, "U":0, "V":0, "W":0, "X":0, "Y":0, "Z":0
};
var listKey = [];

function subLetterToCounters(letter)
{ 
    if (letter == " ")
    {
        return "";
    }
    if (letterCounters[letter] == 0)
    {
        return letter;
    }
    letterCounters[letter] -= 1;
    return "";
};

function addLettersToCounters(letters)
{
    for (var i = 0; i < letters.length; i++)
    {
        var key = letters.charAt(i);
        letterCounters[key] += 1;
    }
};

function clearLetterCounters()
{
    for (var key in letterCounters)
    {
        letterCounters[key] = 0;
    }
};

function countLetterCounters()
{
    var count = 0;
    for (var key in letterCounters)
    {
        for (var i = 0; i < letterCounters[key]; i++)
        { 
            count += 1;
        }    
    }
    return count;
};

function dumpLetterCounters()
{
    var pioche ="";
    for (var key in letterCounters)
    {
        for (var i = 0; i < letterCounters[key]; i++)
        { 
            pioche += (key + " ");
        }    
    }
    return pioche;
};

function initKey(item)
{   
    var count = letterCounters[item.id.split('_')[1]];   
    if (count == 0)
    {
        item.disabled = true;
        item.firstChild.textContent = "" ;
        item.style.backgroundColor = "#808080";
    } 
    else 
    {
        item.disabled = false;
        item.lastChild.textContent = count;
    }
};



function updateKeyCount(item)
{   
    var count = letterCounters[item.id.split('_')[1]];   
    if (item.disabled) 
    { 
        if (count > 0)
        { // disabled node becomes enabled 
            item.lastChild.textContent = count;
            item.style.backgroundColor = "#404040";
            item.disabled = false;

        }
    }
    else
    {
       if (count == 0)
       { // enabled node becomes disabled
           item.disabled = true;
           item.style.backgroundColor = "#808080";
           item.lastChild.textContent = count;    
       } 
       else if (item.lastChild.textContent != count)
       { // count has changed
           item.lastChild.textContent = count;
       }
    }
};

function updateKeyboard()
{
    if ( listKey.length == 0 )
    {
        // create list of keyboard nodes
        listKey = document.getElementsByClassName("btn2");
        // init content of keyboard nodes
        for (var i = 0; i < listKey.length; i++)
        {  
            initKey(listKey[i]);
        }
    }
    else
    {
        // update count of keyboard nodes
        for (var i = 0; i < listKey.length; i++)
        {   
            updateKeyCount(listKey[i]);
        }
    }
};

function updateWithAnagram(word)
{
    if ( listKey.length == 0 )
    {
        // create list of keyboard nodes
        listKey = document.getElementsByClassName("btn2");  
    }
    // update keyboard nodes according letters of word
    for (var i = 0; i < listKey.length; i++)
    { 
        var item = listKey[i];
        if( word.indexOf(item.id.split('_')[1]) > -1 )
        {
             item.disabled = false;
             item.style.backgroundColor = "#404040";
        } 
        else
        {
             item.disabled = true;
             item.style.backgroundColor = "#808080";
        }
    }
};

function updateWithExtract()
{
    if ( listKey.length == 0 )
    {
        // create list of keyboard nodes
        listKey = document.getElementsByClassName("btn2");  
    }
    // activate all keyboard nodes
    for (var i = 0; i < listKey.length; i++)
    { 
        var item = listKey[i];
        item.disabled = false;
        item.style.backgroundColor = "#404040";
    }
};


/* ************************************************* */
function line(letters) {

    var html ='';   
    for (var i = 0; i < letters.length; i++)
    {  
        if (letters.charAt(i) != '<') 
        {  
            html+="<button id=\"k_" + letters.charAt(i) + "\"   class=\"btn2\"  onclick=\"input(this);\" >"
                   + letters.charAt(i) + "<sub>&nbsp</sub></button>";  
        } 
        else
        {  
            html+="<button id=\"eff\"  class=\"eff\"  onclick=\"backspace();\">Eff</button>";  
        }     
    }
    return html;
};

function getKeyboard()
{
    var html = "<div id=\"right_shift\">" + line("AZERTYUIOP") + "</div>";
    html += ("<div id=\"left_shift\" >" + line("QSDFGHJKLM") + "</div>");
    html += ("<div>"+ line("WXCVBN<") + "</div>");
    return html;
};

function input(item)
{ 
    updateLetter(item.id.split('_')[1], false);
};

function backspace()
{ 
    updateLetter('', true);
}; 

/* ************************************************* */
function getCellId(row, col)
{
    return ((row << 6) + col);
};

function getRow(id)
{
    return (id >> 6);
};

function getCol(id)
{
    return (id - ((id >> 6) << 6)); 
};

/* ************************************************* */
// init des references sur tete et pied de fenetre
var tetepage = document.getElementById("tetepage");
var piedpage = document.getElementById("piedpage");
var focusItem;

function takeFocus(item)
{
    if(item == focusItem)
    {
       return;
    }

    if (isNotVisible(item))
    {    
        return;
    }
        
    if (focusItem.getAttribute('class') == "td_sel")
    {
        releaseFocus();
    }

    focusItem = item;
    item.setAttribute('class', "td_sel");
};

function releaseFocus()   
{
    focusItem.setAttribute('class', "td_letter");
};

function isNotVisible(item)
{
    var middle = item.parentNode.offsetTop + item.parentNode.parentNode.parentNode.offsetTop + (item.offsetHeight >> 1);
    var offsetY = window.pageYOffset;
    if (middle < (offsetY + tetepage.offsetHeight))
    {
        return true;
    }
    if (middle > (offsetY + piedpage.offsetTop))
    {
        return true;
    }
    return false;
};
/* ************************************************* */
var MessageType = {
	MT_info : 0,
	MT_error : 1
};

function writeMessage( message, type, show )
{
    if (show)
    { 
        var node = document.getElementById('message');
        node.innerHTML = message;
        node.parentNode.style.display = 'block';
    }
};

function closePopUp()
{
   var node = document.getElementById('message')
   node.value = '';
   node.parentNode.style.display = 'none';

};
/* ************************************************* */
function getListLetters(length, name, grid_id)
{
    // recup de la liste sauvegardee
    if ((typeof window.localStorage != "undefined") && 
        (name in window.localStorage))     
    {   // recup des listes sauvegardees
        var store = window.localStorage.getItem(name).split('_');
        if (grid_id == store[1])
        {
            listelettres =  store[0].toUpperCase().split(',');
            return;
        }            
    } 
    // recup de la liste par defaut
    listelettres= []
    for (var i = 0; i < length; i++)
    { 
        listelettres[i] = " "; 
    }
};

function saveListLetters(name, grid_id)
{
    if (typeof window.localStorage != "undefined") 
    {   
        window.localStorage.setItem(name, listelettres.join() + "_" + grid_id);
    }  
};
/* ************************************************* */
function toggleNodes(node1, node2)
{
    if (node1.style.display == 'none')
    {
        node2.style.display = 'none';
        node1.style.display = 'block';
    }
};
   
function initNodes(node1, node2)
{
    node2.style.display = 'none';
    node1.style.display = 'block';
}; 
/* ************************************************* */

