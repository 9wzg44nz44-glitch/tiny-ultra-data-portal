# TinySA Ultra Data Portal

RF characterization data collection for the Hively / SLW hardware experiments
(Dan Britton & Steve Morris). Captures from the **TinySA Ultra** spectrum
analyzer (CSV and S2P exports) land here, versioned in git, and are served
via GitHub Pages.

## Live page

https://9wzg44nz44-glitch.github.io/tiny-ultra-data-portal/

## What it does

- **Upload form** on the page: pick a CSV or S2P file from the TinySA Ultra's
  mini SD card export (or the PC software), tag it with an experiment ID,
  center frequency, and notes. The file is saved under `data/uploads/` with a
  filename like `DD-433-v2-20261001-143022-433MHz.csv`.
- **File list** on the same page shows everything uploaded so far, with
  download links.
- Everything is a normal git commit, so history, diffs, and sharing with
  Dr. Hively are built in.

## How Steve uploads data

Two ways, both work:

1. **Web form (easiest):** open the live page, choose the file, fill in the
   experiment ID, hit Upload. Works from any browser &mdash; phone, laptop,
   wherever the analyzer's SD card was read.
2. **Git push:** clone this repo, copy files into `data/uploads/`, commit,
   push. Good for bulk transfers or editing the page itself.

## Editing the page

Steve has **write access** to this repo (added as collaborator with push
permission). He can change `index.html`, `upload.php`, styles, anything &mdash;
commit straight to `main` or open a PR. No Grok bot needed; a free GitHub
account is enough.

## CSV format

TinySA Ultra export: one row per frequency point, `frequency_Hz,amplitude_dBm`.
S2P: standard touchstone, any number of ports. The portal does not parse the
contents &mdash; it just stores and serves them.

## Physics context

Hardware under test: dipole detectors (DD-433 / DD-1296 / DD-2450), bifilar
coil, Faraday cage variants, 433 MHz and 1.3 GHz SLW links. See the Scalar
Wave GitHub Pages hub for the broader experiment notes.
