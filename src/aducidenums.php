<?php
/* 
 * Copyright(c) 2012 ANECT a.s.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/**
 * Enumeration of all possible states of AIM server.
 */
class AducidAIMStatus {
    /**
     * No state
     */
    const NONE = "none";
    /**
     * Error on AIM
     */
    const ERROR = "error";
    /**
     * Status working
     */
    const WORKING = "working";
    /**
     * Client binding
     */
    const CLIENT_BINDING = "Client-binding";
    /**
     * Authentication started
     */
    const START = "start";
    /**
     * Authentication finished
     */
    const FINISHED = "finished";
    /**
     * Session is active
     */
    const ACTIVE = "active";
    /**
     * Internal error
     */
    const INTERNAL_ERROR = "internal-error";
    /**
     * Authentication start timeout
     */
    const START_TIMEOUT = "startTimeout";
    /**
     * Authentication process timeout
     */
    const PROCESS_TIMEOUT = "processTimeout";
    /**
     * Authentication binding timeout
     */
    const BINDING_TIMEOUT = "bindingTimeout";
    /**
     * Session ended
     */
    const END = "end";
    /**
     * Passive
     */
    const PASSIVE = "passive";
    /**
     * Error during authentication process
     */
    const AUTH_ERROR = "Auth-error";
}


/**
 * Enumeration of all basic types of directory or pocket personal object. ADUCID
 * Personal objects can be of below types. Each type has it's own special
 * properties and behavior inside ADUCID system.
 */
class AducidAlgorithmName {
    /**
     * Personal object representing set of user attributes
     */
    const USER_ATTR_SET = "USER_ATTRIBUTE_SET";
    /**
     * Personal object representing legacy password stored on PEIG
     */
    const PASSWD = "PASSWD";
    /**
     * Personal object representing password stored on AIM
     */
    const PASSWD_AIM = "PASSWD-AIM";
    /**
     * Personal object representing any strign information on PEIG
     */
    const BIN_STRING = "BIN_STRING";
    /**
     * Personal object representing one time password
     */
    const OTP_AIM_1 = "OTP-AIM_1";
    /**
     * Personal object representing signature tool stored on PEIG
     */
    const X509_SIG = "X509-SIG";
    /**
     * Personal object representing PEIG management
     */
    const PEIG_MGMT = "ADUCID%23%23%23PEIG-MGMT";
    /**
     * Personal object representing payment
     */
    const PAYMENT = "PAYMENT";
}

/**
 * Enumeration of all possible operations with AIM when calling GetPSLAttributes
 * operation.
 */
class AducidAttributeSetName {
    /**
     * Current status of authentication
     */
    const STATUS = "Status";
    /**
     * Basic attributes stored on PSL
     */
    const BASIC = "Basic";
    /**
     * All possible attributes published by authentication process
     */
    const ALL = "All";
    /**
     * Identity validity information
     */
    const VALIDITY = "Validity";
    /**
     * Identity link attributes
     */
    const LINK = "Link";
    /**
     * Error detail
     */
    const ERROR = "Error";
    /**
     * PEIG return name
     */
    const PEIG_RETURN_NAME = "PeigReturnName";
}

/**
 * Enumeration of all possible states which can occur during authentication
 * process.
 */
class AducidAuthStatus {
    /**
     * Positive success
     */
    const OK = "OK";
    /**
     * Unspecified error
     */
    const ERR = "ERR";
    /**
     * Unsupported version
     */
    const UV = "UV";
    /**
     * Unknown service provider
     */
    const USP = "USP";
    /**
     * Duplicate inicialization
     */
    const DI = "DI";
    /**
     * Unsupported security profile
     */
    const UPR = "UPR";
    /**
     * change rollback
     */
    const CR = "CR";
    /**
     * Wrong identification
     */
    const UI = "UI";
    /**
     * Missing identification
     */
    const MI = "MI";
    /**
     * Valid identity
     */
    const VI = "VI";
    /**
     * Unsupported il security profile
     */
    const UIP = "UIP";
    /**
     * Unknown secondary service provider
     */
    const USSP = "USSP";
    /**
     * Not existing extending object
     */
    const NEO = "NEO";
    /**
     * Unsupported extending object profile
     */
    const UOP = "UOP";
    /**
     * Not enough rights
     */
    const NER = "NER";
    /**
     * Selfdestruction
     */
    const IE = "IE";
    /**
     * User rejected operation
     */
    const NAU = "NAU";
    /**
     * Not unique
     */
    const NU = "NU";
    /**
     * Nothing to do
     */
    const NTD = "NTD";
    /**
     * Duplicate replica
     */
    const DR = "DR";
    /**
     * Secondary PEIG error
     */
    const SPE = "SPE";
    /**
     * Not accepted by PEIG
     */
    const NAP = "NAP";
    /**
     * Unknown user
     */
    const UU = "UU";
    /**
     * Unknown user on secondary service provider
     */
    const UUS = "UUS";
    /**
     * Unsupported transformation between security levels
     */
    const UTL = "UTL";
    /**
     * Uncompatibile cipher
     */
    const UCC = "UCC";
    /**
     * No operation requested
     */
    const NOP = "NOP";
    /**
     * Start timeout
     */
    const UIL = "UIL";
    /**
     * Missing ilid
     */
    const ILM = "ILM";
    /**
     * Invalid stamp
     */
    const ISE = "ISE";
    /**
     * Missing secondary service provider address
     */
    const NSA = "NSA";
    /**
     * Positive failure
     */
    const KO = "KO";
    /**
     * Peig not active or missing
     */
    const PPNP = "PPNP";
    /**
     * Non existing session
     */
    const NS = "NS";
    /**
     * Communication timeout
     */
    const CTO = "CTO";
    /**
     * Locked identity
     */
    const LI = "LI";
    /**
     * Duplicit Meeting Room
     */
    const DMR = "DMR";
    /**
     * Unknown Meeting Room
     */
    const UMR = "UMR";
    /**
     * Closed Meeting Room
     */
    const CMR = "CMR";
    /**
     * Meeting Room Enter Time out
     */
    const MET = "MET";
    /**
     * Binding item missing
     */
    const BIM = "BIM";
    /**
     * Requested login name is already used by another PEIG
     */
    const DLN = "DLN";
    /**
     * Meeting room confirmation timeout
     */
    const MCT = "MCT";
    /**
     * Binding evaluation error
     */
    const BEE = "BEE";
    /**
     * Unable binding mode
     */
    const UBM = "UBM";
    /**
     * PEIG copy detected
     */
    const PCD = "PCD";
}

/**
 * External error.
 */
class AducidExternalError {
    /**
     * Unable to authenticate
     */
    const UTA = "UTA";
    /**
     * Authentication session attack
     */
    const ASA = "ASA";
}

/**
 * Enumeration of all methods that can be called on directory or pocket personal
 * object.
 */
class AducidMethodName {
    /**
     * Initialize personal object
     */
    const INIT = "Init";
    /**
     * Create personal object
     */
    const CREATE = "Create";
    /**
     * Change personal object
     */
    const CHANGE = "Change";
    /**
     * Read personal object
     */
    const READ = "Read";
    /**
     * Delete personal object
     */
    const DELETE = "Delete";
    /**
     * Write personal object
     */
    const WRITE = "Write";
    /**
     * Legacy login
     */
    const LEGACY_LOGIN = "LegacyLogin";
    /**
     * Set operation on personal object
     */
    const SET = "Set";
    /**
     * Generate key pairs on PEIG
     */
    const GEN_KEY_PAIR = "GenKeyPair";
    /**
     * Pair certificate with private key stored on PEIG
     */
    const WRITE_CERT = "WriteCert";
    /**
     * Sign with private key
     */
    const LEGACY_SIGN = "LegacySign";
    /**
     * One time password verification
     */
    const OTP_VERIFICATION = "OTPverification";
    /**
     * Identity link read
     */
    const IDENTITY_LINK_READ = "ILread";
    /**
     * Decrypt password
     */
    const PASSWORD_DECRYPT = "PasswdDecrypt";
    /**
     * Set access rights on pesonal object
     */
    const SET_EXECUTE_RIGHT = "SetExecuteRight";
    /**
     * Clear execute rights
     */
    const CLEAR_EXECUTE_RIGHT = "ClearExecuteRight";
    /**
     * Upload certificate (certificate and its private key)
     */
    const UPLOAD_CERT = "UploadCert";
    /**
     * Temporary enable cryptomaterial
     */
    const TEMP_ENABLE_CAPI = "TempEnableCAPI";
    /**
     * Temporary disable cryptomaterial
     */
    const TEMP_DISABLE_CAPI = "TempDisableCAPI";
    /**
     * Permanently enable cryptomaterial
     */
    const PERM_ENABLE_CAPI = "PermEnableCAPI";
    /**
     * Permanently disable cryptomaterial
     */
    const PERM_DISABLE_CAPI = "PermDisableCAPI";
    /**
     * Server side read of personal object
     */
    const PPO_RESULT = "PPOresult";
    /**
     * Server side read of shadow objects (PEIG part of objects stored on
     * server)
     */
    const AIM_READ = "AIMread";
    /**
     * PEIG management - read PEIG count
     */
    const READ_PEIG_COUNT = "ReadPeigCount";
    /**
     * PEIG management - activate other PEIGs
     */
    const ACTIVATE_OTHER_PEIGS = "ActivateOtherPeigs";
    /**
     * PEIG management - deactivate other PEIGs
     */
    const DEACTIVATE_OTHER_PEIGS = "DeactivateOtherPeigs";
    /**
     * PEIG management - activate specific PEIG
     */
    const ACTIVATE_THE_PEIG = "ActivateThePeig";
    /**
     * PEIG management - deactivate specific PEIG
     */
    const DEACTIVATE_THE_PEIG = "DeactivateThePeig";
    /**
     * PEIG management - read current PEIG ID
     */
    const READ_PEIG_ID = "ReadPeigId";
    /**
     * PEIG management - read other PEIGs ID
     */
    const READ_OTHER_PEIGS_ID = "ReadOtherPeigsId";
    /**
     * PEIG management - create room by name
     */
    const CREATE_ROOM_BY_NAME = "CreateRoomByName";
    /**
     * PEIG management - create room by story
     */
    const CREATE_ROOM_BY_STORY = "CreateRoomByStory";
    /**
     * PEIG management - enter room by name
     */
    const ENTER_ROOM_BY_NAME = "EnterRoomByName";
    /**
     * PEIG management - enter room by story
     */
    const ENTER_ROOM_BY_STORY = "EnterRoomByStory";
    /**
     * Payment - confirm transaction
     */
    const CONFIRM_TRANSACTION = "ConfirmTransaction";
    /**
     * LF verification
     */
    const VERIFY_LF = "VerifyLF";
    /**
     * PEIG local link
     */
    const PEIG_LOCAL_LINK = "PeigLocalLink";
}

/**
 * Enumeration of all actions that can be called against AIM.
 */
class AducidOperationName {
    /**
     * Start authentication session
     */
    const OPEN = "open";
    /**
     * Initialize identity
     */
    const INIT = "init";
    /**
     * Reinitialize identity
     */
    const REINIT = "reinit";
    /**
     * Identity change
     */
    const CHANGE = "change";
    /**
     * Identity rechange
     */
    const RECHANGE = "rechange";
    /**
     * Identity delete
     */
    const DELETE = "delete";
    /**
     * Create replica
     */
    const REPLICA = "replica";
    /**
     * Create identity link
     */
    const LINK = "link";
    /**
     * Use personal object
     */
    const EXUSE = "exuse";
    /**
     * Identity auto change
     */
    const AUTO_CHANGE = "autoChange";
}

class AducidTransferMethod {
    /**
     * Transfer authId to PEIG using redirect and AIM proxy
     */
    const REDIRECT = "REDIRECT";
}
?>