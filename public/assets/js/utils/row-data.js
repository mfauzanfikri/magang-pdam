function encodeRowData(rowObject) {
  try {
    return encodeURIComponent(JSON.stringify(rowObject));
  } catch (error) {
    console.error("Failed to encode row data:", error);
    return null;
  }
}


function decodeRowData(encodedValue) {
  try {
    return JSON.parse(decodeURIComponent(encodedValue));
  } catch (error) {
    console.error("Failed to decode row data:", error);
    return null;
  }
}
