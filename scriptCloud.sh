main_function()
{
#Change the entry in file(sshd_config)
sudo sed -i -e 's/PasswordAuthentication no/PasswordAuthentication yes/g' /etc/ssh/sshd_config

#Restart the services
sudo /etc/init.d/sshd restart
sudo service sshd restart

#Add user and assign password to it

sudo useradd -p $(openssl passwd -1 CloudFi) CloudFi

#Adding to the sudoers file
sudo usermod -a -G wheel CloudFi

#Ownership
sudo chown CloudFi:CloudFi /home/CloudFi

#permission
sudo chmod 700 /home/CloudFi/

echo "User created successfully"
}
sudo touch /var/log/scriptCloud.log
sudo chmod 777 /var/log/scriptCloud.log

main_function 2>&1 >> /var/log/scriptCloud.log
echo "User created successfully. Refer /var/log/scriptCloud.log for any logs"
