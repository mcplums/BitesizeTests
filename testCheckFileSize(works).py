import os
statinfo = os.stat('output1.html')
print(statinfo)
print(statinfo.st_size)