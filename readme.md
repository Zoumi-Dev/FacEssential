FacEssential
============
------------
![Image FacEssential](./icon.png)
* **FacEssential** is a Core for **PMMP**, it gathers all kind of plugins needed to create a faction server. It was created from scratch by **Clouds#0667**.

Available commands 
================
----------------
* Table of commands below, the * character means all.

| Command        | Description                                              | Permission       |
|----------------|----------------------------------------------------------|------------------|
| `/tpa`         | Allows you to teleport to a player.                      | all              |
| `/tpahere`     | Allows to teleport to you.                               | all              |
| `/tpaccept`    | Allows you to accept a teleportation request.            | all              |
| `/tpdeny`      | Allows you to refuse a teleportation request.            | all              |
| `/feed`        | Allows you to feed a player or yourself.                 | use.feed         |
| `/heal`        | Allows you to heal a player or yourself.                 | use.heal         |
| `/money`       | Allows you to see your money or a player's money.        | all              |
| `/takemoney`   | Allows you to send money to a player.                    | all              |
| `/addmoney`    | Allows you to add money to a player.                     | use.add.money    |
| `/removemoney` | Allows you to withdraw money from a player.              | use.remove.money |
| `/setmoney`    | Allows you to define a player's money.                   | use.set.money    |
| `/topmoney`    | Allows you to see the top 10 players with the most money.| all              |
| `/craft`       | Allows you to open a crafting table.                     | use.craft        |
| `/enderchest`  | Allows you to open an enderchest.                        | use.enderchest   |
| `/top`         | Allows you to teleport to the surface.                   | use.top          |
-----
Configuration file
==================
------------------
```yaml
# Connect/Disconnect Message
connect-disconnect:
  # Message format. (popup, tip or message)
  format: "popup"
  # Connection message. {player} = name of player (broadcast)
  connect-message: "??7[??2+??7] ??2{player}"
  # Disconnect message. {player} = name of player (broadcast)
  disconnect-message: "??7[??4-??7] ??4{player}"
  # First connection message. {player} = name of player (broadcast message)
  first-connect-message: "??e{player} ??fhas just logged on for the first time, welcome!"

# Combat Logger
combat-logger:
  # How long does it take for the combat logger to come off? (in second)
  cooldown-combat-logger: 15
  # Do you want to display a time remaining message for the combat logger?
  display-cooldown-combat-logger: true

# Teleport
teleport:
  # Do you want players to be immune to each teleportation for a certain time?
  immune-players: false
  # If so, for how long?
  immune-time: 15

# Damage
# Do you allow fall damage?
fall-damage: true
#Do you allow lava damage?
lava-damage: true

# Other
# Set the commands to disabled here.
disable-command:
  - "about"

# MONEY
# Do you want to activate the economy? (money)
enable-money: true
# If so, how much money do players start with?
money-start: 1000
```
Coming Soon
===========
-----------
Soon
----
* none
--------
Commands
--------
* All new orders that will be available.

| Command        | Description                                       | Available for    |
|----------------|---------------------------------------------------|------------------|
| none           | none                                              | none             |
------------------
Configuration file
------------------
* All additions to the configuration file.
```yaml
```
----------------
Support
=======
-------
* If you have any **ideas** for additions or a **bug** to report, please join the server discord by clicking [here](https://discord.gg/kARpD3DsdU).
* To support me financially click [here](https://paypal.me/ZoumiDev)

------
Credit
======
* Thanks to Ayzrix for the Craft order and to the people who suggested ideas!


