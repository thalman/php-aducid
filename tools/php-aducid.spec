Summary: ADUCID class for php 5
Name: php-aducid
Version: @VERSION@
Release: @RELEASE@%{dist}
License: Apache License 2.0
Group: Applications/System
Source1: aducid.php
Source3: aducidenums.php
Source4: index.php
Source5: LICENSE.txt
BuildArch: noarch
BuildRoot: /var/tmp/%{name}-root
Requires: php-soap
Requires: php-xml
Requires: php >= 5.1.0
Packager: Tomas Halman
%define sharedir /usr/share/php
%define cfgdir /etc/httpd/conf.d
%define docdir /usr/share/doc/php-aducid-%{version}


%description
ADUCID classes for php

%prep

%build

%install
install -d ${RPM_BUILD_ROOT}%{sharedir}/aducid
install -d ${RPM_BUILD_ROOT}%{cfgdir}
install -d ${RPM_BUILD_ROOT}%{docdir}/aduciddemo
for file in %{SOURCE1} %{SOURCE3} ; do
    grep -v -i "//FIXME:" $file >${RPM_BUILD_ROOT}%{sharedir}/aducid/`basename $file`
    chmod 644 ${RPM_BUILD_ROOT}%{sharedir}/aducid/`basename $file`
done
#
# anonymize urls
#
sed -r -e 's/^\$aim *=.+$/$aim = "http:\/\/orangebox.example.com";/' \
    -e 's/^\$uim *=.+$/$uim = "https:\/\/orangebox.example.com\/UIM\/";/' \
    %{SOURCE4} >${RPM_BUILD_ROOT}%{docdir}/aduciddemo/index.php
chmod 644 ${RPM_BUILD_ROOT}%{docdir}/aduciddemo/index.php

install -m 644 %{SOURCE5} ${RPM_BUILD_ROOT}%{docdir}/

%clean
rm -rf $RPM_BUILD_ROOT

%files
%doc %{docdir}
%{sharedir}/aducid
