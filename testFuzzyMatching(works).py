from fuzzywuzzy import fuzz
from fuzzywuzzy import process

string1 = 'D-ENTA trouser snake LIMITED'
string2 = 'DENTA LIMITED'

print(fuzz.ratio(string1, string2))
print(fuzz.partial_ratio(string1, string2))
print(fuzz.token_sort_ratio(string1, string2))
print(fuzz.token_set_ratio(string1, string2))
