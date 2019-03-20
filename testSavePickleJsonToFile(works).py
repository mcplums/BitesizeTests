
import json

import pickle



f = open('store.pckl', 'rb')
myTradesList = pickle.load(f)
f.close()
	
jsonString = json.dumps(myTradesList)

f= open("myTradesListJSON.txt","w+")
f.write(jsonString)