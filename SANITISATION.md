# api-rta sanitisation record

This is modern editorial material for the 2026 realTasia archive.

## Publication model

The public repository preserves the master-branch commit sequence, messages, dates and authors. Historical identifying data and detected credential values have been redacted from public file states; those changes produce new commit IDs. The untouched private `pipewrk/api-rta` archival master remains the source. The public commentary links to the rewritten commits.

GitHub continued to serve superseded commits by SHA after this rewrite. Removing those cached views requires separate GitHub cleanup.

## Historical data redactions

Captured agent-directory pages and agent photographs under `agents/` and `stagents/` were removed because they identify agents and contain contact or professional details. Thirteen uploaded photographs showing identifiable people, including copies at 42 historical paths, were also removed. Seven identifying rows in six captured `company/` pages were redacted; other rows remain. These are data changes in retained commits, not wholesale removal of those commits.

The following historical commits touched those paths. The first identifier is from the private source; the link opens its rewritten public counterpart. Subsequent commit IDs also change where their ancestry changes.

| Original commit | Public commit | Original message |
|---|---|---|
| `413cbd2` | [`5216c41`](https://github.com/realtasia/api-rta/commit/5216c4175570f049443f86ccc7c92243fafe16c8) | Finally removed all extra files. |
| `fa4d723` | [`1d98809`](https://github.com/realtasia/api-rta/commit/1d9880933a26f0b29d1dc4c8326dd4bb7daa6fa1) | Updated media uploads. was not working. Updated agent db I think. |
| `dd6adf8` | [`9468154`](https://github.com/realtasia/api-rta/commit/94681543e6040aac1548aff81864849599051fb3) | New agent files, halfway done with private messaging |
| `e4d238f` | [`2ba6d1a`](https://github.com/realtasia/api-rta/commit/2ba6d1a249904428e4bcadfccb099679d4a5b23b) | Optimised Images |
| `10780bf` | [`7896341`](https://github.com/realtasia/api-rta/commit/78963414909d2784087c14ed20adef317de180e0) | moving up the album / attachment functionality |
| `ceb3974` | [`d0318f2`](https://github.com/realtasia/api-rta/commit/d0318f21cb12442d2be83b3129b71b1daea78377) | Changes to user |
| `dd93eca` | [`841b7a0`](https://github.com/realtasia/api-rta/commit/841b7a0d7c72746a31da96c8aad34f44fa13f6ce) | Completed most if not all functionality for api. Not tested |
| `3369300` | [`b0ba3e1`](https://github.com/realtasia/api-rta/commit/b0ba3e182b1d07bb0427098783830c305d92d9b2) | removing image uploads |
| `bd2b83f` | [`ec5319e`](https://github.com/realtasia/api-rta/commit/ec5319e5f1623e8858ceddb6271e876198e14d52) | Adding media testing |
| `8822f9e` | [`41e231f`](https://github.com/realtasia/api-rta/commit/41e231f76864a9b270309ddc6e16133702aaf8a5) | Solving emails and smses |
| `595392e` | [`9d63f4e`](https://github.com/realtasia/api-rta/commit/9d63f4ece4570565513298437addc73045c33ff5) | Committing core classes for oauth2 |

### Personal details in examples

Personal email addresses, phone numbers, sample account identifiers and named contact examples embedded in historical test and interface data were replaced with explicit redaction markers. General author attribution and the surrounding source remain. The following commits introduced or changed those examples; later identifiers also change where ancestry changes.

| Original commit | Public commit | Original message |
|---|---|---|
| `746f780` | [`105a480`](https://github.com/realtasia/api-rta/commit/105a4804d815106602922db86d04a28e40d2236c) | Committing most hovercards markup & css |
| `2d4cdb2` | [`729214a`](https://github.com/realtasia/api-rta/commit/729214aed1d7e85fe5aa8c1f5648042cd84b0cab) | More files for templating more pages |
| `a987157` | [`9933493`](https://github.com/realtasia/api-rta/commit/993349337c84e8a8204d9ff976641bd69b992c5d) | More standadisation. Last few forms creation |
| `87104f5` | [`c6ec51f`](https://github.com/realtasia/api-rta/commit/c6ec51f887a36299dc7495625b0054259bbe6650) | Committing kohana application modules |
| `595392e` | [`9d63f4e`](https://github.com/realtasia/api-rta/commit/9d63f4ece4570565513298437addc73045c33ff5) | Committing core classes for oauth2 |
| `c28d643` | [`36d3763`](https://github.com/realtasia/api-rta/commit/36d37632d64612d682f14ac8101df9d52ac34f38) | Completed user module api. |
| `d67feee` | [`8a23c45`](https://github.com/realtasia/api-rta/commit/8a23c455515576424912ce0de00bc260ff722ad0) | More fixes & enhancements to user API |
| `2fd9888` | [`06fd8ba`](https://github.com/realtasia/api-rta/commit/06fd8ba6caa402558c0667252fe6f41856bbed29) | Adding some routes |
| `e53fb42` | [`37a1b67`](https://github.com/realtasia/api-rta/commit/37a1b67739938d384ebb37098152409a5ff67e4e) | Created new transport for IE cross domain requests. All is well now :) |
| `8822f9e` | [`41e231f`](https://github.com/realtasia/api-rta/commit/41e231f76864a9b270309ddc6e16133702aaf8a5) | Solving emails and smses |
| `da6d10a` | [`a7388a1`](https://github.com/realtasia/api-rta/commit/a7388a1c2b27166d12cdd7a609f5e29d225c9863) | Small utility to export dbs |
| `7efcaa1` | [`407cb5f`](https://github.com/realtasia/api-rta/commit/407cb5ffe377fc0c142ed2e3d575443ea8fbd2c2) | Pushing mods and changes |
| `ceb3974` | [`d0318f2`](https://github.com/realtasia/api-rta/commit/d0318f21cb12442d2be83b3129b71b1daea78377) | Changes to user |
| `dd6adf8` | [`9468154`](https://github.com/realtasia/api-rta/commit/94681543e6040aac1548aff81864849599051fb3) | New agent files, halfway done with private messaging |
| `57a9f26` | [`c70dc60`](https://github.com/realtasia/api-rta/commit/c70dc60028597257f7fe38013d7b2d809932ee63) | Mods to registration flow. Prepping for test deploy |
| `8085cae` | [`b4787d4`](https://github.com/realtasia/api-rta/commit/b4787d4bc2967051a0023e719e710278691e22df) | Added new functionality for an admin page for debugging. |
| `73347f0` | [`2884e6a`](https://github.com/realtasia/api-rta/commit/2884e6a901f1a505f724bd4a0dbc860ac6c07d71) | Updated admin functionality |
| `ab0218e` | [`2fe3c6d`](https://github.com/realtasia/api-rta/commit/2fe3c6da8071b11dfab1d01486ca5e9e7aa1d83d) | Emails for account creation and activation. Templates set and sort of tested. SMS also sent to my phone in the interim |
| `26cab8e` | [`0df86cd`](https://github.com/realtasia/api-rta/commit/0df86cd7b216e5d209bb787601c5416fadff1652) | Fixed New User subscription to me. Fixed user email links in activation email |

Affected paths:

- `application/classes/controller/post.php`
- `application/classes/controller/test.php`
- `application/classes/controller/user.php`
- `application/views/OLD_TEMPLATE_FILES/misc.php`
- `application/views/email/activation-email.php`
- `application/views/emails/content/plaintext_generic.php`
- `application/views/emails/spam_mail.php`
- `application/views/misc.php`
- `companyProfile.php`
- `index.php`
- `locationProfile.php`
- `misc.php`

## Historical credential redactions

Detected AWS access IDs and associated secret values, credential and token values in configuration, source, and examples were replaced with explicit redaction markers throughout the public history. The surrounding files, commit sequence and commit metadata remain. This includes values that GitHub push protection identified; fixture keys are marked as such here without treating them as evidence of active credentials.

The following commits introduced or changed detected values. The first identifier refers to the private source; the link opens the rewritten public commit. Later commit IDs may also differ because their ancestry changed.

| Original commit | Public commit | Original message |
|---|---|---|
| `595392e` | [`9d63f4e`](https://github.com/realtasia/api-rta/commit/9d63f4ece4570565513298437addc73045c33ff5) | Committing core classes for oauth2 |
| `c28d643` | [`36d3763`](https://github.com/realtasia/api-rta/commit/36d37632d64612d682f14ac8101df9d52ac34f38) | Completed user module api. |
| `d67feee` | [`8a23c45`](https://github.com/realtasia/api-rta/commit/8a23c455515576424912ce0de00bc260ff722ad0) | More fixes & enhancements to user API |
| `5cdd866` | [`3efc5bd`](https://github.com/realtasia/api-rta/commit/3efc5bd2f41adc9514fc55ddb5dda75cca1173b6) | Fixes to users, imported singpost & started on posts |
| `d5977cd` | [`cfdcf7a`](https://github.com/realtasia/api-rta/commit/cfdcf7a672b4c9f4a61378b1db6796aeb1993f40) | New testing interface |
| `9385eb2` | [`459752b`](https://github.com/realtasia/api-rta/commit/459752bc4bc3502f30c9bcd8fc8c7ec2b0f90de2) | Changes to singpost helps filter out more nonsense |
| `da6d10a` | [`a7388a1`](https://github.com/realtasia/api-rta/commit/a7388a1c2b27166d12cdd7a609f5e29d225c9863) | Small utility to export dbs |
| `10780bf` | [`7896341`](https://github.com/realtasia/api-rta/commit/78963414909d2784087c14ed20adef317de180e0) | moving up the album / attachment functionality |
| `b136717` | [`11380e6`](https://github.com/realtasia/api-rta/commit/11380e664350caf9d0b9aa85c8a13e1d43245eac) | Removed trailing spaces |
| `98cc0b6` | [`2cb96b0`](https://github.com/realtasia/api-rta/commit/2cb96b0b2046ef96a621412010fa5392032d0e1f) | Moved alot of core functionality to a module because Queue depends on Models. |
| `68216fa` | [`1ef3669`](https://github.com/realtasia/api-rta/commit/1ef366900f3b16f6aefd6e86fa8512f6c1a8cfab) | Made some changes to auth controller |

Affected paths:

- `application/classes/amazonsdk.php`
- `application/classes/aws.php`
- `application/classes/controller/singpost.php`
- `application/classes/controller/test.php`
- `application/config/amazon.php`
- `application/config/email.php`
- `application/config/encrypt.php`

## Snapshot changes

| Category | Treatment | Reason | Behavioural effect |
|---|---|---|---|
| Historical uploads and captured agent, company and property datasets | Omitted | They may contain identifiable people, property imagery and operational records | Historical sample data and uploads are unavailable |
| Elastic Beanstalk configuration and generated result files | Omitted | Private deployment metadata and incidental generated data are not part of the public artefact | Original deployment automation is incomplete |
| AWS, email, encryption, Facebook, MongoDB, OAuth, reCAPTCHA, authentication and session configuration | Operational values replaced with synthetic values | Credentials, account identifiers and private topology must not be published | Integrations require new configuration |
| Reviewed test, user, correction, location and QR-code paths | Confirmed personal or operational literals replaced with synthetic values | Real people and private operating details are excluded | Application logic remains; affected examples are synthetic |

`COMMIT_COMMENTARY.md` is contemporary analysis. Its links point to the corresponding rewritten public commits.

## Rights

No open-source licence is granted for this repository as a whole. Historical third-party notices continue to govern the files they cover. All other rights are reserved.
