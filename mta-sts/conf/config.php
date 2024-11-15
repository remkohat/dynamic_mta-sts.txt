<?php

#    Mode: When sending to an MX at a domain for which the sender has a valid,
#          non-expired MTA-STS Policy, a Sending MTA honoring MTA-STS applies
#          the result of a policy validation failure in one of two ways,
#          depending on the value of the policy "mode" field:
#
#       1. "enforce": In this mode, Sending MTAs MUST NOT deliver the
#          message to hosts that fail MX matching or certificate validation
#          or that do not support STARTTLS.
#
#       2. "testing": In this mode, Sending MTAs that also implement the
#          TLSRPT (TLS Reporting) specification [RFC8460] send a report
#          indicating policy application failures (as long as TLSRPT is also
#          implemented by the recipient domain); in any case, messages may
#          be delivered as though there were no MTA-STS validation failure.
#
#       3. "none": In this mode, Sending MTAs should treat the Policy Domain
#          as though it does not have any active policy.
#
$mode = "enforce";

# Max Age: Max lifetime of the policy (plaintext non-negative
#          integer seconds, maximum value of 31557600).  Well-behaved clients
#          SHOULD cache a policy for up to this value from the last policy
#          fetch time.  To mitigate the risks of attacks at policy refresh
#          time, it is expected that this value typically be in the range of
#          weeks or greater.
#
#          The value must be between 86400 (1 day) and 31557600 (about 1 year).
#
#          If mode is set to "none" then "max_age" will be set to 86400 (1 day).
#
$max_age = "7776000";

?>