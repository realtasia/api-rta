# Dispatches from the API

An annotated selection from the surviving RealtAsia API history.

This is not a cleaned-up changelog. The original messages are quoted exactly because they are part of the record: occasionally precise, frequently candid, and sometimes written at the point where an engineer and a browser had exhausted all diplomatic channels.

Dates below are those preserved in the repository history.

---

## September 2012: define “final”

> **[Pushing final design for everything but messaging & notifications & settings.](https://github.com/realtasia/api-rta/commit/2f834dfbeeab1543991e9c22ba0a1f4391c1e9b5)**  
> 29 September 2012

“Final” is immediately qualified by three substantial areas of the product that are not final.

This is funny, but it also locates the state of RealtAsia at the time. Profiles, listings and the broader interface already existed; messaging, notification preferences and settings were the next product boundaries. Those supposedly incidental exceptions would eventually become conversations, per-event notification policy, background workers and live socket delivery.

The commit message accidentally supplies the roadmap.

---

## 20–22 October 2012: implementing OAuth while OAuth 2.0 became a standard

> **[oauth implementation complete, adding main controller](https://github.com/realtasia/api-rta/commit/59d61bcad7457460154d95a299a59e8b3136455f)**  
> 20 October 2012

The diff introduces separate client, resource and session models alongside the authentication service and controller. Its own design notes identify client authentication, password authentication, token issuance, token validation, expiry and consistent errors as distinct responsibilities.

Two days later:

> **[oauth resource implementation complete.](https://github.com/realtasia/api-rta/commit/e1d71635c5feb6493fc22432da1635fb0234d39c)**  
> 22 October 2012

The API gains a protected-resource controller, bearer-token validation, structured success and error responses, password and client-credentials routing, state propagation and the resource boundary used by the rest of the application.

RFC 6749, the OAuth 2.0 standard, was published in October 2012. This was not a mature ecosystem in which a framework plugin settled the question. RealtAsia was implementing the parts it needed while the standard itself was still arriving.

The surviving code is application-specific rather than a complete general-purpose OAuth provider. That makes the timing more interesting, not less: the architecture was responding to the actual boundary between the browser application and a separately deployed API.

---

## 24 October 2012: the interaction model explains itself

> **[Created simpler MongoDB models. Completed interactions. It now auto updates the user(interactor) and the interacted object through a cool new feature in the model called 'before_save' and 'after_save'. Interactions check if an entry exists before insertions. Likes now automatically create a subscription as well.](https://github.com/realtasia/api-rta/commit/56669c1c27b032ac8610a14a58319ac58665b20a)**

This is less a commit message than an architecture note that happened to fit into Git.

The important decisions are all present:

- interactions are first-class records rather than loose arrays;
- duplicate relationships are prevented;
- lifecycle hooks maintain the interacting user and target object;
- derived counts and recent participants are updated with the relationship;
- liking something also subscribes the person to future activity.

One canonical social edge could therefore support both directions: what a person follows, and who follows a particular object. Fast display data was projected onto the object without making that projection the source of truth.

The phrase “cool new feature” is justified.

---

## 26 October 2012: completion, consequences and Users

> **[Finished Interactions & Tested. Likes add a Subscribe. Checks for comment likes & add subscribe to parent object. Finished comments, retrieval, creation and adding a subscribe. Still to come, Posts. Pages. Users. Listings. Users next & a bitch.](https://github.com/realtasia/api-rta/commit/72331212cf002ebbfeb4cd47c37be2c81a023b2f)**

This commit clarifies the social semantics.

A like establishes continuing interest. A liked comment cannot sensibly become the centre of a subscription, so the subscription is attached to its parent post. Comment creation also subscribes its author. These rules allow later activity to reach the right feed and notification audiences without every feature inventing a separate follower system.

Then the message surveys the remaining domain:

> Posts. Pages. Users. Listings.

And correctly identifies Users as the difficult one. Profiles sat at the intersection of identity, privacy, companies, roles, billing, sales attribution, social relationships and authentication. “Users next & a bitch” is informal, but its architectural risk assessment is sound.

---

## 2–4 November 2012: confidence encounters reality

> **[More fixes to user. Notification retrieval retrieves the whole object i think](https://github.com/realtasia/api-rta/commit/7859b93c9fd97591692ab32d461f13e86c3d586e)**  
> 2 November

“I think” is an unusually responsible suffix for a commit touching nested MongoDB data extraction.

The following day:

> **[nope, not fixed](https://github.com/realtasia/api-rta/commit/5e40603925597dd07e74f150b56417ed5d7dbbbf)**  
> 3 November

The commit disables a broad exception wrapper so the underlying request failure can finally escape in its natural form. This is the debugging equivalent of removing the theatre curtains because something backstage is on fire.

Then, on 4 November:

> **[ok it works, now a small mod to the users collection](https://github.com/realtasia/api-rta/commit/25f1e73671fda84d4476db3ddda687d7c5d45336)**  
> 09:29

The “small mod” changes a MongoDB privacy path from `settings.privacy.is` to `settings.privacy.0.is`.

Four minutes later, the browser enters the story.

> **[IE not working. trying former headers](https://github.com/realtasia/api-rta/commit/0204553b5433b51ed4fa7d4ddb0a6a0363ef7f8f)**  
> 09:33

The former header is P3P, a compact policy declaration once used to persuade Internet Explorer to accept cross-domain cookies.

> **[Still not working in ie. logging request params](https://github.com/realtasia/api-rta/commit/bd25edc5dc3b021c2f1e1be6850ce5b75bca4ddc)**  
> 09:38

Five minutes have passed. Diplomacy has failed. Surveillance begins.

Finally:

> **[Created new transport for IE cross domain requests. All is well now :)](https://github.com/realtasia/api-rta/commit/37a1b67739938d384ebb37098152409a5ff67e4e)**  
> 19:38

The solution is not another header. It adds an explicit cross-domain transport for Internet Explorer, built around the browser's period-specific request mechanism.

The smiley was earned. It took approximately ten hours and a new transport protocol to restore it.

---

## 20 November 2012: maximal honesty at the integration boundary

> **[Completed most if not all functionality for api. Not tested](https://github.com/realtasia/api-rta/commit/841b7a0d7c72746a31da96c8aad34f44fa13f6ce)**

This should be read as a development checkpoint, not a declaration that the live product was never tested or completed.

The commit is enormous. It touches authentication, resources, users, pages, posts, media, privacy, interactions and the underlying document and collection abstractions. It also brings in a considerable set of uploaded test media.

“Most if not all” acknowledges uncertainty about scope. “Not tested” records that this integrated state had not yet been exercised. The subsequent history contains months of exactly that exercise: UI integration, bug correction, browser compatibility, feed implementation, listings, messaging, billing, infrastructure and production changes.

This is what a useful historical commit does. It tells the next reader where confidence ends.

---

## 24 December 2012: Christmas Eve user debugging

> **[Added a qrcode class](https://github.com/realtasia/api-rta/commit/d4d61191bb09175a11781a9f502b28e9c383b662)**  
> 13:17

Forty minutes later:

> **[bug in get user](https://github.com/realtasia/api-rta/commit/e84600e80e48645ae51b287cdc1173eff25341de)**  
> 13:57

Four minutes after that:

> **[bug in get user still](https://github.com/realtasia/api-rta/commit/735399ccd4f96ea237f78f018064dca698b23a7d)**  
> 14:01

At 20:09:

> **[Company was not being udate when a user changed it](https://github.com/realtasia/api-rta/commit/2ab8f0502531b10510cccb922feaee9104874efb)**

Nothing says Christmas Eve quite like maintaining referential consistency between a professional profile and its company membership.

The sequence also supports the earlier assessment. Users were, indeed, next and a bitch.

---

## 31 January 2013: deletion means deleting the whole thing

> **[Added to delete post functionality. Now handles albums and attachments & deletes S3 stored files](https://github.com/realtasia/api-rta/commit/c913a230e1d62c424f1a7e909f9da41f67887b00)**

This is one of the less comic and more consequential commits.

Deleting a social object had effects beyond removing one database document. Albums owned attachments; media had several S3 variants; counts and relationships had to remain coherent.

The commit extends deletion across that boundary. It is evidence that S3 was part of the product's storage model, not a folder where uploads were casually abandoned.

---

## 7 March 2013: Ticker arrives; Minion is permitted to remain

> **[Added ticker functionality and modified the feed method for a user. tried out minion, but doubt there will be use. leaving it in for now.](https://github.com/realtasia/api-rta/commit/d2c129b7d8f4992be5b46f8967314bcb3cc780f0)**

Ticker changes the feed from a query over whatever posts happen to be reachable into a maintained per-user projection.

New content creates a ticker record for its author and queues distribution to followers of the author and associated page. Comments and positive interactions increase relevance, with `hasInteracted` preventing the same person from repeatedly contributing the same signal. The feed endpoint retrieves ranked ticker IDs and then hydrates the corresponding posts.

Alongside this, Minion—the Kohana task runner—is evaluated.

The verdict is decisive:

> doubt there will be use. leaving it in for now.

Every repository contains at least one dependency serving a custodial sentence without conviction.

---

## 24 June 2013: certainty varies by subsystem

> **[Updated media uploads. was not working. Updated agent db I think.](https://github.com/realtasia/api-rta/commit/1d9880933a26f0b29d1dc4c8326dd4bb7daa6fa1)**

The media portion is clear: it was not working, and it was updated.

The agent database portion is represented by hundreds of generated or processed agent files organised alphabetically. “I think” is not confusion about whether agents existed; it is the exhausted acknowledgement that the commit has crossed enough concerns that even its author is no longer prepared to certify the inventory from memory.

This is also a glimpse of the data work surrounding the product. RealtAsia was not operating against a toy collection of three hand-entered estate agents.

---

## 18 August 2013: an authentication loop achieves sentience

> **[Setting latest changes as remote is not attempting new token each request causing logs to go berserk](https://github.com/realtasia/api-rta/commit/941d4006510a46065f4ed6526132111d96a367ff)**

The remote application was attempting to obtain a fresh token on every request. The authentication layer responded by filling the logs with its objections.

“Logs to go berserk” is both diagnosis and observability report.

The commit itself includes cached framework state as well as controller changes, a reminder that debugging deployment-specific PHP behaviour often involved committing whichever collection of artefacts appeared to make the remote machine stop screaming.

---

## 25 September 2013: two hard systems, four words

> **[Completed listings, conversations.](https://github.com/realtasia/api-rta/commit/e896c8e4ef90314ccf3939ec7866068d1afee541)**

Listings carried property-specific data, location relationships, media, visibility, lifecycle and search concerns.

Conversations were represented through posts and dynamic message comments, with participants, unread ownership, searchable participant keywords, message counts, summaries and notification jobs.

The commit message allocates two words to each.

This is perhaps the defining rhythm of the repository: long messages explain one interaction rule in minute detail; four words announce the completion of two major product subsystems.

---

## What this history shows

The API history captures the domain taking shape rather than merely accumulating endpoints.

Across these commits:

1. OAuth creates a boundary between client applications, people and protected resources.
2. Interactions become canonical, bidirectionally queryable social relationships.
3. Domain hooks maintain fast projections and counts.
4. Engagement establishes subscriptions and future audiences.
5. User privacy, identity and company membership expose the difficult joins.
6. Browser incompatibility forces a separate transport.
7. S3 media becomes part of deletion integrity.
8. Ticker materialises and ranks individual feeds.
9. Conversations reuse the post and comment model while adding participant-specific state.

The accompanying narration remains irreducibly human:

> Notification retrieval retrieves the whole object i think  
> nope, not fixed  
> ok it works  
> Still not working in ie  
> All is well now :)  
> Users next & a bitch  
> Updated agent db I think  
> logs to go berserk

These are not synthetic summaries written after the architecture became obvious. They are field notes from inside its construction.
