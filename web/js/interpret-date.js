function interpretDate(entryDate, dateString) {
  entry = new Date(entryDate * 1000);
  return entry + '' + ' ? + ' + dateString;
}
