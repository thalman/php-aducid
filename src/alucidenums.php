<?php

class AlucidAction {
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

class AlucidAuthStatus {
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
     * Duplicate login name
     */
    const DLN = "DLN";
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
    const STO = "STO";
    /**
     * Process timeout
     */
    const PTO = "PTO";
    /**
     * Unknown ilid
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
     * CAPI support
     */
    const CNS = "CNS";
}

class AlucidPSLAttributesSet {
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
}

class AlucidPersonalObjectMethod {
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
     * Server side read of shadow objects (PEIG part of objects stored on server)
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
}

class AlucidTransferMethod {
    /**
     * Transfer authId to PEIG using redirect and AIM proxy
     */
    const REDIRECT = "REDIRECT";
}

?>
