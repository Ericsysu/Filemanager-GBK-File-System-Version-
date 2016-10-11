var a;
var b=['Link','Image','Flash'];
var c;
var d=['Browser','Upload'];
for (a in b) for(c in d) FCKConfig[b[a]+d[c]+'URL']=FCKConfig.BasePath+'plugins/pgrfilemanager/PGRFileManager.php?langCode='+FCKConfig.DefaultLanguage+'&type='+b[a];
