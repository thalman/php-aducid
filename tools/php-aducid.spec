Summary: ADUCID class for php 5
Name: php-aducid
Version: 3.0.0
Release: 1%{dist}
License: Apache License 2.0
Group: Applications/System
Source: php-aducid.tgz
BuildArch: noarch
BuildRoot: /var/tmp/%{name}-root
Requires: php-soap
Requires: php-xml
Requires: php >= 5.1.0
Packager: Tomas Halman
%define sharedir /usr/share/php
%define docdir /usr/share/doc/php-aducid-%{version}

%description
ADUCID classes for php

%prep
%setup -n php-aducid

%build

%install
# create directory structure
# --------------------------
install -d ${RPM_BUILD_ROOT}%{sharedir}/aducid
install -d ${RPM_BUILD_ROOT}%{docdir}

# install application files
# -------------------------
for file in ./src/*.php ; do
    grep -E -v -i "^[ \t]*//" $file >${RPM_BUILD_ROOT}%{sharedir}/aducid/`basename $file`
    chmod 644 ${RPM_BUILD_ROOT}%{sharedir}/aducid/`basename $file`
done

# install documentation
# ---------------------
install -m 644 LICENSE.md ${RPM_BUILD_ROOT}%{docdir}/
install -m 644 README.md ${RPM_BUILD_ROOT}%{docdir}/

# demo application
# ----------------
install -d ${RPM_BUILD_ROOT}%{docdir}/demos
for file in doc/demos/{bg.jpg,demo.css} ; do
    install -m 644 $file ${RPM_BUILD_ROOT}%{docdir}/demos/
done
for file in doc/demos/*.php ; do
    basefile=$(basename $file)
    grep -E -v -i "^[ \t]*//" $file | \
    sed -r -e 's/^\$GLOBALS\[.aim.\] *=.+$/\$GLOBALS["aim"] = "http:\/\/orangebox.example.com";/' \
    > ${RPM_BUILD_ROOT}%{docdir}/demos/$basefile
    chmod 644 ${RPM_BUILD_ROOT}%{docdir}/demos/$basefile
done

# testing application
# -------------------
install -d ${RPM_BUILD_ROOT}%{docdir}/testing-application
install -d ${RPM_BUILD_ROOT}%{docdir}/testing-application/images
for file in tests/images/* ; do
    install -m 644 $file ${RPM_BUILD_ROOT}%{docdir}/testing-application/images
done
for file in tests/*.{php,md,css} ; do
    basefile=$(basename $file)
    grep -E -v -i "^[ \t]*//" $file | \
    sed -r -e 's/^\$GLOBALS\[.aim.\] *=.+$/\$GLOBALS["aim"] = "http:\/\/orangebox.example.com";/' \
    > ${RPM_BUILD_ROOT}%{docdir}/testing-application/$basefile
    chmod 644 ${RPM_BUILD_ROOT}%{docdir}/testing-application/$basefile
done

%clean
rm -rf $RPM_BUILD_ROOT

%files
%doc %{docdir}
%{sharedir}/aducid
