#Overview:
#This script checks the likelihood that a password has been leaked.
#It does so by by submitting the 
#first 5 characters of password SHA-1's has to the haveibeenpwned API
#
#Disclaimer: 
#This tool is not a password generator. It is simply used as a 'leak check'
#A stronger password should be longer.
#See: https://xkcd.com/936/
#
#Credit:
#API: https://haveibeenpwned.com/API/v2
#Based of the Computerphile episode: https://www.youtube.com/watch?v=hhUb5iknVJs

import sys; 
import hashlib; 
import requests; 
import re; 

#this function submits the first 5 SHA1 chars to pwnedpasswords
#it returns the number of time the password has been pwned
def subPwnPass(hashpassw, PRINT=False):
    hashpassw=hashpassw.upper()
    url = "https://api.pwnedpasswords.com/range/"
    r = requests.get(url+hashpassw[0:5])
    res = r.text

    if PRINT:
        print(hashpassw[0:5])
        print(hashpassw[5:])
    retVal = 0

    matchObj = re.findall(r'('+hashpassw[5:]+r')\:(\d+)', res)
    if matchObj:
        if PRINT:
            print(matchObj)
            print("Password found %s %s times." % matchObj[0])
        retVal = matchObj[0][1]
    else:
        print("Password has not been pwned.")
    
    if PRINT:
        print(r.text)
    
    return retVal; 

def main():
    args = sys.argv
    if len(args) != 2:
        print("Usage: python check_pass_pwd.py PASSWORD")
        exit()
    else:
        #password hashing
        uPass = str(args[1]); 
        sha1Pass = hashlib.sha1(uPass.encode('ASCII'))
        sha1PassHex = sha1Pass.hexdigest()
        print("args: %s, %s" % (args[0], args[1]))
        print("SHA1: %s" % sha1PassHex)
        pwnedVal = subPwnPass(sha1PassHex, False)
        print("Password pwned %s times" % pwnedVal)

#run main program
main()