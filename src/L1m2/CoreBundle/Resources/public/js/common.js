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
    if (focusItem.getAttribute('class') == "td_sel")
    {
        releaseFocus();
    }
    if (isNotVisible(item))
    {
        return;
    }
    focusItem = item;
    item.setAttribute('class', "td_sel");
    item.childNodes[1].focus();
};

function releaseFocus()   
{
    focusItem.childNodes[1].blur(); 
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
function getBackFlag(event)
{
    var key = event.keyCode; 
    return ((key == 8) || (key == 13));
};

function keyToVal(event, backFlag, item)
{
    var key = event.keyCode;
  
    if (((key < 65) || (key > 90)) && !backFlag && (key != 32))
    {  // touches non traitees 
         return false;   
    }

    if (isNotVisible(item.parentNode))
    {    
        return false;
    }

    if (!backFlag)
    {
        item.value = String.fromCharCode(key).toUpperCase();
    }
    else 
    {
        item.value = " ";
    } 

    return true; 
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

