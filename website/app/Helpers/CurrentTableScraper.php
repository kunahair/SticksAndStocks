<?php

/**
 * Extract data from a given DOM object. Pushes heading key and value onto specified dataArray.
 * If there is an error getting Key or Value, then the object is not added to dataArray.
 * @param $dom - DOM object, passed by reference
 * @param $dataArray - dataArray to push key-value onto, passed by reference
 * @param null $heading - Key that is either a react-id (int) or manual heading (String)
 * @param null $reactID - Value that is the react-id (int) of the given element in the DOM
 * @return null
 */
function extractCurrentTableRowFromDom(&$dom, &$dataArray, $heading = null, $reactID = null)
{
    //If heading or reactID are null, then there is nothing to grab so return nothing
    if ($heading == null || $reactID == null)
        return null;

    //Outer accessible heading to be used as key
    $heading1 = null;
    //Outer accessible value to be used as value
    $value = null;

    //TryCatch block in case there is an error getting the elements from the Table
    try {
        //If the heading parameter is not a string, then get the Key value from the selected element in the DOM object
        if (!is_string($heading))
            $heading1 = $dom->find('[data-reactid=' . $heading . ']')->text();
        else
            //Otherwise, assume the heading has been set manually and make the Key the heading
            $heading1 = $heading;

        $heading1 = str_replace(" ", "", $heading1);
        $heading1 = str_replace(".", "", $heading1);
        $heading1 = str_replace(")", "", $heading1);
        $heading1 = str_replace(")", "", $heading1);

        //Extract the Value, given by the reactID, from the DOM object
        $value = $dom->find('[data-reactid=' . $reactID . ']')->text();

    }catch (\Exception $exception)
    {
        //todo: write exception to some log file for debugging, maybe should return true or false?
    }

    //Add the Key-Value to the given dataArray
    //NOTE: Done this way so when it is called multiple times in a row, the ultimate JSON will show a list
    //of JSON objects instead of one big JSON object.
//    array_push($dataArray, [$heading1 => $value]);

    $dataArray[$heading1] = $value;
    //Returns null as it is a mutator function, nothing to return
    return null;
}

























